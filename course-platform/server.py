# -*- coding: utf-8 -*-
from flask import Flask, jsonify, request, send_from_directory
import json

app = Flask(__name__, static_folder='.')
DATA_FILE = 'courses.json'

def load_courses():
    with open(DATA_FILE, 'r', encoding='utf-8') as f:
        return json.load(f)

def save_courses(courses):
    with open(DATA_FILE, 'w', encoding='utf-8') as f:
        json.dump(courses, f, ensure_ascii=False, indent=2)

def parse_majors(major_str):
    """解析逗号分隔的专业字符串，返回专业列表"""
    if not major_str:
        return []
    return [m.strip() for m in major_str.split('、') if m.strip()]

def build_college_map(courses):
    college_map = {}
    all_colleges_ordered = []
    for c in courses:
        cid = c['产业学院']
        if cid not in college_map:
            college_map[cid] = {'name': cid, 'majors': {}}
            all_colleges_ordered.append(cid)
        # 解析逗号分隔的专业
        major_str = c.get('专业方向', '')
        for m in parse_majors(major_str):
            if m not in college_map[cid]['majors']:
                college_map[cid]['majors'][m] = 0
            college_map[cid]['majors'][m] += 1
    return college_map, all_colleges_ordered

# ========== 公开只读 API ==========

@app.route('/api/init')
def api_init():
    courses = load_courses()
    # 计算唯一课程数量（按课程名去重）
    unique_courses = set(c['课程'] for c in courses)
    return jsonify({'status': 'ok', 'colleges': 9, 'courses': len(unique_courses)})

@app.route('/api/colleges')
def api_colleges():
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    result = []
    for i, name in enumerate(all_colleges_ordered, 1):
        data = college_map[name]
        result.append({
            'id': i,
            'name': name,
            'icon': 'school',
            'major_count': len(data['majors']),
            'course_count': sum(data['majors'].values()),
            'majors': None
        })
    return jsonify(result)

@app.route('/api/majors')
def api_majors():
    college_id = request.args.get('college_id', '0')
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    cid_int = int(college_id) if str(college_id).isdigit() else 0
    result = []
    if cid_int > 0 and cid_int <= len(all_colleges_ordered):
        college_name = all_colleges_ordered[cid_int - 1]
        for mname, count in college_map[college_name]['majors'].items():
            result.append({
                'id': len(result) + 1,
                'name': mname,
                'college_id': cid_int,
                'college_name': college_name,
                'course_count': count
            })
    return jsonify(result)

@app.route('/api/courses')
def api_courses():
    college_id = request.args.get('college_id', '0')
    major_id = request.args.get('major_id', '0')
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)

    cid_int = int(college_id) if str(college_id).isdigit() else 0
    mid_int = int(major_id) if str(major_id).isdigit() else 0

    major_id_map = {}
    mid_counter = 1
    for i, cname in enumerate(all_colleges_ordered, 1):
        for mname in college_map[cname]['majors'].keys():
            major_id_map[(i, mname)] = mid_counter
            mid_counter += 1

    result = []
    for c in courses:
        cname = c['产业学院']
        mname_raw = c['专业方向']
        parsed_majors = parse_majors(mname_raw)
        cid = all_colleges_ordered.index(cname) + 1
        # 为每个专业都创建一条记录
        for m in parsed_majors:
            mid = major_id_map.get((cid, m), 1)
            if cid_int > 0 and cid != cid_int:
                continue
            if mid_int > 0 and mid != mid_int:
                continue
            result.append({
                'id': len(result) + 1,
                'name': c['课程'],
                'major_id': mid,
                'college_id': cid,
                'college_name': cname,
                'major_name': m,
                'image_url': c.get('image_url', ''),
                'description': c.get('description', ''),
                'online_url': c.get('online_url', ''),
                'offline_url': c.get('offline_url', '')
            })
    return jsonify(result)

@app.route('/api/login', methods=['POST'])
def api_login():
    data = request.get_json()
    username = data.get('username', '').strip()
    password = data.get('password', '')
    if username == 'admin' and password == 'admin123':
        import base64
        token = base64.b64encode(f'{username}|ok'.encode()).decode()
        return jsonify({'token': token, 'username': username})
    return jsonify({'error': '用户名或密码错误'}), 401

# ========== 认证中间件 ==========

def require_auth():
    auth = request.headers.get('Authorization', '')
    if not auth.startswith('Bearer '):
        return jsonify({'error': '未授权'}), 401
    return None

# ========== 学院管理 ==========

@app.route('/api/admin/colleges', methods=['GET'])
def admin_list_colleges():
    err = require_auth()
    if err: return err
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    result = []
    for i, name in enumerate(all_colleges_ordered, 1):
        data = college_map[name]
        result.append({
            'id': i,
            'name': name,
            'major_count': len(data['majors']),
            'course_count': sum(data['majors'].values())
        })
    return jsonify(result)

@app.route('/api/admin/colleges', methods=['POST'])
def admin_add_college():
    err = require_auth()
    if err: return err
    data = request.get_json()
    new_name = data.get('name', '').strip()
    if not new_name:
        return jsonify({'error': '学院名称不能为空'}), 400
    courses = load_courses()
    # 检查重名
    for c in courses:
        if c['产业学院'] == new_name:
            return jsonify({'error': '学院已存在'}), 400
    # 在每条记录的产业学院列新增占位（不影响实际数据）
    courses.append({'产业学院': new_name, '专业方向': '新专业方向', '课程': '新课程'})
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/colleges/<int:college_id>', methods=['PUT'])
def admin_update_college(college_id):
    err = require_auth()
    if err: return err
    data = request.get_json()
    new_name = data.get('name', '').strip()
    if not new_name:
        return jsonify({'error': '学院名称不能为空'}), 400
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    if college_id < 1 or college_id > len(all_colleges_ordered):
        return jsonify({'error': '学院不存在'}), 404
    old_name = all_colleges_ordered[college_id - 1]
    for c in courses:
        if c['产业学院'] == old_name:
            c['产业学院'] = new_name
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/colleges/<int:college_id>', methods=['DELETE'])
def admin_delete_college(college_id):
    err = require_auth()
    if err: return err
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    if college_id < 1 or college_id > len(all_colleges_ordered):
        return jsonify({'error': '学院不存在'}), 404
    old_name = all_colleges_ordered[college_id - 1]
    # 只保留不属于该学院的课程
    courses = [c for c in courses if c['产业学院'] != old_name]
    save_courses(courses)
    return jsonify({'status': 'success'})

# ========== 专业管理 ==========

@app.route('/api/admin/majors', methods=['GET'])
def admin_list_majors():
    err = require_auth()
    if err: return err
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    result = []
    for i, cname in enumerate(all_colleges_ordered, 1):
        for mname, count in college_map[cname]['majors'].items():
            result.append({
                'id': len(result) + 1,
                'name': mname,
                'college_id': i,
                'college_name': cname,
                'course_count': count
            })
    return jsonify(result)

@app.route('/api/admin/majors', methods=['POST'])
def admin_add_major():
    err = require_auth()
    if err: return err
    data = request.get_json()
    name = data.get('name', '').strip()
    college_id = int(data.get('college_id') or 0)
    if not name:
        return jsonify({'error': '专业名称不能为空'}), 400
    if college_id < 1:
        return jsonify({'error': '请选择所属学院'}), 400
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    if college_id > len(all_colleges_ordered):
        return jsonify({'error': '学院不存在'}), 404
    college_name = all_colleges_ordered[college_id - 1]
    courses.append({'产业学院': college_name, '专业方向': name, '课程': '新课程'})
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/majors/<int:major_id>', methods=['PUT'])
def admin_update_major(major_id):
    err = require_auth()
    if err: return err
    data = request.get_json()
    new_name = data.get('name', '').strip()
    if not new_name:
        return jsonify({'error': '专业名称不能为空'}), 400
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    # 找到第 major_id 个专业
    all_majors = []
    for i, cname in enumerate(all_colleges_ordered, 1):
        for mname in college_map[cname]['majors'].keys():
            all_majors.append((i, mname))
    if major_id < 1 or major_id > len(all_majors):
        return jsonify({'error': '专业不存在'}), 404
    old_college_id, old_mname = all_majors[major_id - 1]
    old_college_name = all_colleges_ordered[old_college_id - 1]
    for c in courses:
        if c['产业学院'] == old_college_name and c['专业方向'] == old_mname:
            c['专业方向'] = new_name
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/majors/<int:major_id>', methods=['DELETE'])
def admin_delete_major(major_id):
    err = require_auth()
    if err: return err
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    all_majors = []
    for i, cname in enumerate(all_colleges_ordered, 1):
        for mname in college_map[cname]['majors'].keys():
            all_majors.append((i, mname))
    if major_id < 1 or major_id > len(all_majors):
        return jsonify({'error': '专业不存在'}), 404
    old_college_id, old_mname = all_majors[major_id - 1]
    old_college_name = all_colleges_ordered[old_college_id - 1]
    courses = [c for c in courses if not (c['产业学院'] == old_college_name and c['专业方向'] == old_mname)]
    save_courses(courses)
    return jsonify({'status': 'success'})

# ========== 课程管理 ==========

@app.route('/api/admin/courses', methods=['GET'])
def admin_list_courses():
    err = require_auth()
    if err: return err
    college_id = request.args.get('college_id', '0')
    major_id = request.args.get('major_id', '0')
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)

    major_id_map = {}
    mid_counter = 1
    for i, cname in enumerate(all_colleges_ordered, 1):
        for mname in college_map[cname]['majors'].keys():
            major_id_map[(i, mname)] = mid_counter
            mid_counter += 1

    cid_int = int(college_id) if str(college_id).isdigit() else 0
    mid_int = int(major_id) if str(major_id).isdigit() else 0

    result = []
    for c in courses:
        cname = c['产业学院']
        mname = c['专业方向']
        cid = all_colleges_ordered.index(cname) + 1
        mid = major_id_map.get((cid, mname), 1)
        if cid_int > 0 and cid != cid_int:
            continue
        if mid_int > 0 and mid != mid_int:
            continue
        result.append({
            'id': len(result) + 1,
            'name': c['课程'],
            'major_id': mid,
            'college_id': cid,
            'college_name': cname,
            'major_name': mname,
            'image_url': c.get('image_url', ''),
            'description': c.get('description', ''),
            'online_url': c.get('online_url', ''),
            'offline_url': c.get('offline_url', '')
        })
    return jsonify(result)

@app.route('/api/admin/courses', methods=['POST'])
def admin_add_course():
    err = require_auth()
    if err: return err
    data = request.get_json()
    name = data.get('name', '').strip()
    college_id = int(data.get('college_id') or 0)
    major_id = int(data.get('major_id') or 0)
    if not name:
        return jsonify({'error': '课程名称不能为空'}), 400
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)
    if college_id < 1 or college_id > len(all_colleges_ordered):
        return jsonify({'error': '学院不存在'}), 404
    college_name = all_colleges_ordered[college_id - 1]
    # 找该学院下的专业
    majors = list(college_map[college_name]['majors'].keys())
    if major_id < 1:
        major_name = majors[0] if majors else '默认专业'
    else:
        # major_id 全局编号 -> 找到对应专业
        all_majors = []
        for i, cname in enumerate(all_colleges_ordered, 1):
            for mname in college_map[cname]['majors'].keys():
                all_majors.append((i, mname))
        if major_id > len(all_majors):
            major_name = majors[0] if majors else '默认专业'
        else:
            major_name = all_majors[major_id - 1][1]
    courses.append({
        '产业学院': college_name,
        '专业方向': major_name,
        '课程': name,
        'image_url': data.get('image_url', ''),
        'description': data.get('description', ''),
        'online_url': data.get('online_url', ''),
        'offline_url': data.get('offline_url', '')
    })
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/courses/<int:course_id>', methods=['PUT'])
def admin_update_course(course_id):
    err = require_auth()
    if err: return err
    data = request.get_json()
    courses = load_courses()
    college_map, all_colleges_ordered = build_college_map(courses)

    all_courses = []
    for c in courses:
        all_courses.append(c)

    if course_id < 1 or course_id > len(all_courses):
        return jsonify({'error': '课程不存在'}), 404

    c = all_courses[course_id - 1]
    if 'name' in data: c['课程'] = data['name']
    if 'image_url' in data: c['image_url'] = data['image_url']
    if 'description' in data: c['description'] = data['description']
    if 'online_url' in data: c['online_url'] = data['online_url']
    if 'offline_url' in data: c['offline_url'] = data['offline_url']
    # 允许修改所属学院和专业
    if 'college_id' in data or 'major_id' in data:
        new_college_id = int(data.get('college_id') or 0)
        new_major_id = int(data.get('major_id') or 0)
        if new_college_id > 0 and new_college_id <= len(all_colleges_ordered):
            c['产业学院'] = all_colleges_ordered[new_college_id - 1]
            if new_major_id > 0:
                # 找该学院下的第N个专业
                college_name = all_colleges_ordered[new_college_id - 1]
                majors = list(college_map[college_name]['majors'].keys())
                if new_major_id <= len(majors):
                    c['专业方向'] = majors[new_major_id - 1]

    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/admin/courses/<int:course_id>', methods=['DELETE'])
def admin_delete_course(course_id):
    err = require_auth()
    if err: return err
    courses = load_courses()
    if course_id < 1 or course_id > len(courses):
        return jsonify({'error': '课程不存在'}), 404
    courses.pop(course_id - 1)
    save_courses(courses)
    return jsonify({'status': 'success'})

@app.route('/api/course_update', methods=['POST'])
def api_course_update():
    return jsonify({'status': 'success'})

@app.route('/api/upload', methods=['POST'])
def api_upload():
    if 'file' not in request.files:
        return jsonify({'error': '没有文件'}), 400
    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': '没有选择文件'}), 400
    import os
    upload_dir = os.path.join(os.path.dirname(__file__), 'uploads')
    if not os.path.exists(upload_dir):
        os.makedirs(upload_dir)
    # 生成唯一文件名
    import uuid
    ext = os.path.splitext(file.filename)[1]
    filename = f"{uuid.uuid4().hex}{ext}"
    filepath = os.path.join(upload_dir, filename)
    file.save(filepath)
    return jsonify({'url': f'/uploads/{filename}'})

@app.route('/uploads/<path:filename>')
def serve_uploads(filename):
    return send_from_directory('uploads', filename)

# ========== 静态文件 ==========

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/admin/')
def admin():
    return send_from_directory('.', 'admin.html')

if __name__ == '__main__':
    print('启动服务器 http://localhost:5000')
    app.run(host='0.0.0.0', port=5000, debug=True)
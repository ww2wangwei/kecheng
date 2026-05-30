<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理后台 - 课程资源平台</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --primary:#1a73e8;--primary-dark:#1557b0;--primary-light:#e8f0fe;
  --purple:#8b5cf6;--bg:#f8f9fc;--card-bg:#fff;--text:#1f2937;
  --text-light:#6b7280;--border:#e5e7eb;--shadow:0 2px 8px rgba(0,0,0,.08);
  --shadow-lg:0 8px 24px rgba(0,0,0,.12);--radius:12px;--radius-sm:8px;
  --success:#34a853;--danger:#dc3545;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
a{text-decoration:none;color:inherit}

/* Login page */
.login-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;background:linear-gradient(135deg,#1a73e8,#6c2bd9,#8b5cf6)}
.login-box{background:#fff;border-radius:16px;padding:40px;width:100%;max-width:400px;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.login-box h1{font-size:24px;font-weight:800;margin-bottom:8px;text-align:center}
.login-box p{font-size:14px;color:var(--text-light);text-align:center;margin-bottom:32px}
.login-box label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--text)}
.login-box input{width:100%;padding:12px 16px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;outline:none;transition:border .2s;margin-bottom:16px}
.login-box input:focus{border-color:var(--primary)}
.login-box button{width:100%;padding:13px;background:linear-gradient(135deg,var(--primary),var(--purple));color:#fff;border:none;border-radius:var(--radius-sm);font-size:15px;font-weight:600;cursor:pointer;transition:box-shadow .2s}
.login-box button:hover{box-shadow:0 4px 16px rgba(26,115,232,.4)}
.login-box .error{color:var(--danger);font-size:13px;text-align:center;margin-bottom:12px;display:none}
.login-box .back-link{display:block;text-align:center;margin-top:16px;font-size:13px;color:var(--text-light)}
.login-box .back-link:hover{color:var(--primary)}

/* Admin header */
.admin-header{background:#fff;border-bottom:1px solid var(--border);padding:0 24px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.admin-header .left{display:flex;align-items:center;gap:24px}
.admin-header .logo{font-size:18px;font-weight:800;color:var(--primary);display:flex;align-items:center;gap:8px}
.admin-header .right{font-size:14px;color:var(--text-light);display:flex;align-items:center;gap:16px}
.admin-header .logout{color:var(--danger);cursor:pointer;font-weight:500}

/* Admin layout */
.admin-body{max-width:1200px;margin:0 auto;padding:24px}
.filter-bar{background:#fff;border-radius:var(--radius);padding:20px;margin-bottom:20px;display:flex;gap:12px;align-items:center;flex-wrap:wrap;box-shadow:var(--shadow)}
.filter-bar select,.filter-bar input{padding:8px 14px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;outline:none}
.filter-bar select:focus,.filter-bar input:focus{border-color:var(--primary)}
.filter-bar .count{font-size:13px;color:var(--text-light);margin-left:auto}

/* Course table */
.course-table{background:#fff;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow)}
.course-table table{width:100%;border-collapse:collapse}
.course-table th{background:#f8f9fc;padding:12px 16px;text-align:left;font-size:13px;font-weight:600;color:var(--text-light);border-bottom:1px solid var(--border)}
.course-table td{padding:12px 16px;font-size:14px;border-bottom:1px solid var(--border);vertical-align:middle}
.course-table tr:last-child td{border-bottom:none}
.course-table tr:hover td{background:#f8f9fc}
.course-table .name-cell{max-width:200px}
.course-table .name-cell .name{font-weight:600}
.course-table .name-cell .meta{font-size:12px;color:var(--text-light)}
.course-table .url-cell{max-width:150px}
.course-table .url-cell a{color:var(--primary);font-size:12px;word-break:break-all}
.course-table .url-cell .empty{color:var(--text-light);font-size:12px}
.course-table .edit-btn{background:var(--primary);color:#fff;border:none;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap}
.course-table .edit-btn:hover{background:var(--primary-dark)}

/* Edit modal */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;z-index:1000;padding:20px;backdrop-filter:blur(4px)}
.modal{background:#fff;border-radius:16px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.modal-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
.modal-header h2{font-size:18px;font-weight:700}
.modal-close{background:none;border:none;font-size:24px;cursor:pointer;color:var(--text-light)}
.modal-body{padding:20px 24px}
.modal-body label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--text)}
.modal-body input,.modal-body textarea{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-size:14px;outline:none;resize:vertical;font-family:inherit}
.modal-body input:focus,.modal-body textarea:focus{border-color:var(--primary)}
.modal-body textarea{min-height:80px}
.modal-body .field{margin-bottom:16px}
.modal-body .btn-row{display:flex;gap:10px;justify-content:flex-end;margin-top:20px}
.modal-body .btn-save{background:linear-gradient(135deg,var(--primary),var(--purple));color:#fff;border:none;padding:10px 24px;border-radius:20px;font-size:14px;font-weight:600;cursor:pointer}
.modal-body .btn-cancel{background:#fff;border:1.5px solid var(--border);padding:10px 24px;border-radius:20px;font-size:14px;font-weight:600;cursor:pointer}
.modal-body .btn-row-bottom{display:flex;gap:10px;margin-top:20px}

/* Pagination */
.pagination{display:flex;justify-content:center;gap:8px;padding:20px}
.pagination button{padding:8px 14px;border:1px solid var(--border);background:#fff;border-radius:var(--radius-sm);cursor:pointer;font-size:13px;transition:all .2s}
.pagination button:hover{background:var(--primary-light);border-color:var(--primary);color:var(--primary)}
.pagination button.active{background:var(--primary);color:#fff;border-color:var(--primary)}

@media(max-width:768px){
  .filter-bar{flex-direction:column;align-items:stretch}
  .filter-bar select,.filter-bar input{width:100%}
  .course-table{overflow-x:auto}
}
</style>
</head>
<body>

<div id="admin-app"></div>

<script>
const API = '/api';
let token = sessionStorage.getItem('admin_token') || '';
let username = sessionStorage.getItem('admin_username') || '';
let state = { page:1, collegeFilter:0, majorFilter:0, courses:[], colleges:[], majors:[], total:0, editing:null };

async function api(url, method='GET', body=null){
  const opts = {method, headers:{'Content-Type':'application/json'}};
  if(token) opts.headers['Authorization'] = 'Bearer ' + token;
  if(body) opts.body = JSON.stringify(body);
  const res = await fetch(API + url, opts);
  if(res.status===401){ logout(); throw new Error('Unauthorized'); }
  return res.json();
}

function render(){ document.getElementById('admin-app').innerHTML = token ? renderAdmin() : renderLogin(); }

function renderLogin(){
  return `<div class="login-page">
    <div class="login-box">
      <h1>管理后台</h1>
      <p>请输入管理员账号密码登录</p>
      <div class="error" id="login-error"></div>
      <input type="text" id="login-user" placeholder="用户名" autocomplete="username">
      <input type="password" id="login-pass" placeholder="密码" autocomplete="current-password" onkeydown="if(event.key==='Enter')doLogin()">
      <button onclick="doLogin()">登录</button>
      <a class="back-link" href="/">← 返回前台</a>
    </div>
  </div>`;
}

function renderAdmin(){
  const collegeOptions = `<select id="filter-college" onchange="onCollegeFilter()"><option value="0">全部学院</option>
    ${state.colleges.map(c=>`<option value="${c.id}" ${state.collegeFilter==c.id?'selected':''}>${escHtml(c.name)}</option>`).join('')}
  </select>`;
  const majorOptions = `<select id="filter-major" onchange="onMajorFilter()"><option value="0">全部专业</option>
    ${state.majors.map(m=>`<option value="${m.id}" ${state.majorFilter==m.id?'selected':''}>${escHtml(m.name)}</option>`).join('')}
  </select>`;
  return `<div class="admin-header">
    <div class="left">
      <div class="logo">🎓 课程管理后台</div>
    </div>
    <div class="right">
      <span>${escHtml(username)}</span>
      <a href="/" style="color:var(--text-light)">前台</a>
      <span class="logout" onclick="logout()">退出</span>
    </div>
  </div>
  <div class="admin-body">
    <div class="filter-bar">
      ${collegeOptions}
      ${majorOptions}
      <input type="text" id="search-input" placeholder="搜索课程名..." onkeydown="if(event.key==='Enter')doSearch()">
      <button onclick="doSearch()" style="padding:8px 16px;background:var(--primary);color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:14px">搜索</button>
      <div class="count">共 ${state.total} 门课程</div>
    </div>
    <div class="course-table">
      <table>
        <thead><tr>
          <th>ID</th><th>课程名称</th><th>学院</th><th>专业方向</th><th>线上地址</th><th>线下地址</th><th>操作</th>
        </tr></thead>
        <tbody id="course-tbody"></tbody>
      </table>
    </div>
    <div class="pagination" id="pagination"></div>
  </div>`;
}

function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}

async function doLogin(){
  const u = document.getElementById('login-user').value.trim();
  const p = document.getElementById('login-pass').value;
  if(!u||!p){ document.getElementById('login-error').style.display='block'; document.getElementById('login-error').textContent='请输入用户名和密码'; return; }
  try {
    const res = await api('/login.php', 'POST', {username:u, password:p});
    token = res.token;
    username = res.username;
    sessionStorage.setItem('admin_token', token);
    sessionStorage.setItem('admin_username', username);
    render();
    await loadData();
  } catch(e){
    document.getElementById('login-error').style.display='block';
    document.getElementById('login-error').textContent = e.message||'登录失败';
  }
}

function logout(){ token=''; username=''; sessionStorage.clear(); render(); }

async function loadData(){
  const [colleges, majors, courses] = await Promise.all([
    api('/colleges.php'),
    api('/majors.php?college_id=0'),
    api(`/courses.php?college_id=${state.collegeFilter}&major_id=${state.majorFilter}`)
  ]);
  state.colleges = colleges;
  state.majors = majors;
  state.courses = courses;
  state.total = courses.length;
  render();
  renderTable();
}

function renderTable(){
  const start = 0; // 简化：一次显示全部
  const pageCourses = state.courses.slice(start, start + 50);
  document.getElementById('course-tbody').innerHTML = pageCourses.map(c=>`
    <tr>
      <td>${c.id}</td>
      <td class="name-cell"><div class="name">${escHtml(c.name)}</div></td>
      <td>${escHtml(c.college_name||'')}</td>
      <td>${escHtml(c.major_name||'')}</td>
      <td class="url-cell">${c.online_url ? `<a href="${escHtml(c.online_url)}" target="_blank">${escHtml(c.online_url).substring(0,30)}…</a>` : '<span class="empty">未填写</span>'}</td>
      <td class="url-cell">${c.offline_url ? `<a href="${escHtml(c.offline_url)}" target="_blank">${escHtml(c.offline_url).substring(0,30)}…</a>` : '<span class="empty">未填写</span>'}</td>
      <td><button class="edit-btn" onclick="openEdit(${c.id})">编辑</button></td>
    </tr>`).join('');
}

function openEdit(courseId){
  const c = state.courses.find(x=>x.id==courseId);
  if(!c) return;
  state.editing = c;
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.id = 'edit-modal';
  modal.innerHTML = `
    <div class="modal">
      <div class="modal-header">
        <h2>编辑课程</h2>
        <button class="modal-close" onclick="closeEdit()">×</button>
      </div>
      <div class="modal-body">
        <div class="field"><label>课程名称</label><input id="e-name" value="${escHtml(c.name)}" readonly style="background:#f3f4f6"></div>
        <div class="field"><label>课程图片URL</label><input id="e-image" value="${escHtml(c.image_url||'')}" placeholder="https://..."></div>
        <div class="field"><label>课程简介</label><textarea id="e-desc" placeholder="输入课程简介...">${escHtml(c.description||'')}</textarea></div>
        <div class="field"><label>线上课程地址</label><input id="e-online" value="${escHtml(c.online_url||'')}" placeholder="https://..."></div>
        <div class="field"><label>线下课程地址</label><input id="e-offline" value="${escHtml(c.offline_url||'')}" placeholder="https://..."></div>
        <div class="btn-row-bottom">
          <button class="btn-cancel" onclick="closeEdit()">取消</button>
          <button class="btn-save" onclick="saveEdit(${courseId})">保存</button>
        </div>
      </div>
    </div>`;
  modal.addEventListener('click', e=>{ if(e.target===modal) closeEdit(); });
  document.body.appendChild(modal);
}

function closeEdit(){ const m=document.getElementById('edit-modal'); if(m) m.remove(); state.editing=null; }

async function saveEdit(courseId){
  const data = {
    image_url: document.getElementById('e-image').value.trim(),
    description: document.getElementById('e-desc').value.trim(),
    online_url: document.getElementById('e-online').value.trim(),
    offline_url: document.getElementById('e-offline').value.trim()
  };
  try {
    await api(`/course_update.php?id=${courseId}`, 'POST', data);
    closeEdit();
    await loadData();
  } catch(e){ alert('保存失败: '+e.message); }
}

function onCollegeFilter(){
  state.collegeFilter = parseInt(document.getElementById('filter-college').value);
  state.majorFilter = parseInt(document.getElementById('filter-major').value);
  loadData();
}
function onMajorFilter(){
  state.collegeFilter = parseInt(document.getElementById('filter-college').value);
  state.majorFilter = parseInt(document.getElementById('filter-major').value);
  loadData();
}
function doSearch(){
  const q = document.getElementById('search-input').value.trim().toLowerCase();
  // 重新加载并过滤
  loadData().then(()=>{
    if(q){
      state.courses = state.courses.filter(c=>c.name.toLowerCase().includes(q));
      state.total = state.courses.length;
      render();
      renderTable();
    }
  });
}

// init
render();
if(token){ loadData(); }
</script>
</body>
</html>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>课程资源平台</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --primary:#1a73e8;
  --primary-dark:#1557b0;
  --primary-light:#e8f0fe;
  --purple:#8b5cf6;
  --purple-dark:#6c2bd9;
  --bg:#f8f9fc;
  --card-bg:#fff;
  --text:#1f2937;
  --text-light:#6b7280;
  --border:#e5e7eb;
  --shadow:0 2px 8px rgba(0,0,0,.08);
  --shadow-lg:0 8px 24px rgba(0,0,0,.12);
  --radius:12px;
  --radius-sm:8px;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh}
a{text-decoration:none;color:inherit}

/* Header */
.header{background:linear-gradient(135deg,#1a73e8 0%,#8b5cf6 100%);color:#fff;padding:0}
.header-inner{max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px}
.header .logo{font-size:20px;font-weight:800;display:flex;align-items:center;gap:10px}
.header .logo svg{width:32px;height:32px}
.header nav{display:flex;gap:8px}
.header nav a{padding:6px 16px;border-radius:20px;font-size:14px;font-weight:500;color:#fff;opacity:.85;transition:all .2s}
.header nav a:hover,.header nav a.active{opacity:1;background:rgba(255,255,255,.2)}
.header .admin-btn{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);padding:6px 16px;border-radius:20px;font-size:14px;font-weight:500;color:#fff;cursor:pointer;transition:all .2s}
.header .admin-btn:hover{background:rgba(255,255,255,.25)}

/* Hero */
.hero{background:linear-gradient(135deg,#1a73e8 0%,#6c2bd9 50%,#8b5cf6 100%);color:#fff;padding:64px 24px;text-align:center;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");opacity:.5}
.hero h1{font-size:36px;font-weight:800;margin-bottom:12px;position:relative}
.hero p{font-size:16px;opacity:.85;position:relative}
.hero .stats{display:flex;justify-content:center;gap:48px;margin-top:32px;position:relative}
.hero .stat{text-align:center}
.hero .stat .num{font-size:32px;font-weight:800}
.hero .stat .label{font-size:13px;opacity:.8;margin-top:4px}

/* Search */
.search-bar{max-width:600px;margin:-28px auto 32px;position:relative;z-index:2}
.search-bar input{width:100%;padding:16px 20px;padding-left:48px;font-size:15px;border:none;border-radius:50px;box-shadow:var(--shadow-lg);background:#fff;outline:none}
.search-bar input:focus{box-shadow:0 4px 20px rgba(26,115,232,.3)}
.search-bar{position:relative}
.search-bar svg{position:absolute;left:16px;top:50%;transform:translateY(-50%);color:var(--text-light)}

/* Container */
.container{max-width:1200px;margin:0 auto;padding:0 24px 48px}

/* Section title */
.section-title{font-size:22px;font-weight:700;margin-bottom:20px;color:var(--text)}

/* College grid */
.college-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;margin-top:24px}

/* College card */
.college-card{background:var(--card-bg);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);border:1px solid var(--border);cursor:pointer;transition:all .25s;position:relative;overflow:hidden}
.college-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--primary),var(--purple))}
.college-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.college-card .icon{width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,var(--primary-light),#f3e8ff);display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:24px}
.college-card h3{font-size:17px;font-weight:700;margin-bottom:8px}
.college-card p{font-size:13px;color:var(--text-light);line-height:1.5;margin-bottom:16px}
.college-card .badge{display:inline-block;background:var(--primary-light);color:var(--primary);font-size:12px;font-weight:600;padding:3px 10px;border-radius:20px;margin-right:6px;margin-bottom:4px}
.college-card .arrow{position:absolute;right:20px;top:50%;transform:translateY(-50%);color:var(--text-light);transition:all .2s}
.college-card:hover .arrow{translate:4px;color:var(--primary)}

/* Major/Course list */
.list-header{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.list-header .back-btn{background:#fff;border:1px solid var(--border);padding:8px 16px;border-radius:20px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s}
.list-header .back-btn:hover{background:var(--primary-light);border-color:var(--primary);color:var(--primary)}
.list-header h2{font-size:20px;font-weight:700}
.major-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}
.major-card{background:#fff;border-radius:var(--radius);padding:20px;box-shadow:var(--shadow);border:1px solid var(--border);cursor:pointer;transition:all .2s}
.major-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-lg)}
.major-card h4{font-size:15px;font-weight:600;margin-bottom:4px}
.major-card span{font-size:12px;color:var(--text-light)}

/* Course list */
.course-list{display:flex;flex-direction:column;gap:12px}
.course-item{background:#fff;border-radius:var(--radius);padding:20px 24px;box-shadow:var(--shadow);border:1px solid var(--border);cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:space-between}
.course-item:hover{transform:translateX(4px);border-color:var(--primary);box-shadow:0 4px 16px rgba(26,115,232,.12)}
.course-item .info{display:flex;align-items:center;gap:16px;flex:1}
.course-item .num{width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary-light),#f3e8ff);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--primary)}
.course-item h4{font-size:15px;font-weight:600}
.course-item .meta{font-size:12px;color:var(--text-light)}
.course-item .arrow-right{color:var(--text-light);transition:all .2s}
.course-item:hover .arrow-right{translate:4px;color:var(--primary)}

/* Course modal */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;z-index:1000;padding:20px;backdrop-filter:blur(4px)}
.modal{background:#fff;border-radius:16px;max-width:520px;width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.modal-header{padding:24px 24px 0;display:flex;align-items:flex-start;justify-content:space-between}
.modal-header h2{font-size:22px;font-weight:800}
.modal-close{background:none;border:none;font-size:24px;cursor:pointer;color:var(--text-light);padding:4px;line-height:1}
.modal-body{padding:20px 24px 28px}
.modal-body .course-img{width:100%;height:180px;border-radius:var(--radius);background:linear-gradient(135deg,var(--primary-light),#f3e8ff);display:flex;align-items:center;justify-content:center;margin-bottom:16px;overflow:hidden}
.modal-body .course-img img{max-width:100%;max-height:100%;object-fit:cover}
.modal-body .course-img .placeholder{font-size:48px;opacity:.3}
.modal-body .desc{font-size:14px;color:var(--text-light);line-height:1.7;margin-bottom:20px}
.modal-body .links{display:flex;flex-direction:column;gap:10px}
.modal-body .link-btn{display:flex;align-items:center;justify-content:center;gap:10px;padding:14px;border-radius:var(--radius-sm);font-size:15px;font-weight:600;transition:all .2s;border:none;cursor:pointer}
.modal-body .link-btn.online{background:linear-gradient(135deg,var(--primary),var(--purple));color:#fff}
.modal-body .link-btn.online:hover{box-shadow:0 4px 16px rgba(26,115,232,.4)}
.modal-body .link-btn.offline{background:#fff;border:2px solid var(--primary);color:var(--primary)}
.modal-body .link-btn.offline:hover{background:var(--primary-light)}
.modal-body .link-btn.empty{background:#f3f4f6;color:var(--text-light);cursor:not-allowed}
.modal-body .no-links{text-align:center;padding:24px;color:var(--text-light);font-size:14px}

/* Admin bar */
.admin-bar{text-align:center;padding:32px;font-size:14px;color:var(--text-light)}

/* Responsive */
@media(max-width:768px){
  .hero h1{font-size:26px}
  .hero .stats{gap:24px}
  .hero .stat .num{font-size:24px}
  .college-grid{grid-template-columns:1fr}
  .major-grid{grid-template-columns:1fr}
  .modal{width:100%}
}

/* Animation */
.fade-in{animation:.3s ease-out fadeIn}
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

/* Page loader */
.page-loader{min-height:200px;display:flex;align-items:center;justify-content:center;color:var(--text-light)}
.page-loader::after{content:'';width:32px;height:32px;border:3px solid var(--border);border-top-color:var(--primary);border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>

<!-- Header -->
<header class="header">
<div class="header-inner">
  <div class="logo">
    <svg viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="8" fill="rgba(255,255,255,.2)"/><path d="M8 22V10l8-4 8 4v12" stroke="#fff" stroke-width="2" fill="none"/><path d="M6 12h20v10a2 2 0 01-2 2H8a2 2 0 01-2-2V12z" fill="rgba(255,255,255,.3)"/><path d="M14 22v-6h4v6" stroke="#fff" stroke-width="2"/></svg>
    课程资源平台
  </div>
  <nav>
    <a href="#" onclick="showHome()" class="active" id="nav-home">首页</a>
    <a href="#" onclick="showColleges()" id="nav-colleges">全部学院</a>
  </nav>
  <button class="admin-btn" onclick="goAdmin()">管理后台</button>
</div>
</header>

<!-- Main content -->
<main id="app"></main>

<script>
const API = '/api';
let state = { view:'home', colleges:[], collegeId:null, majorId:null, courses:[], college:null, major:null };

async function api(url) {
  const res = await fetch(API + url);
  if (!res.ok) throw new Error(res.statusText);
  return res.json();
}

function render(){
  const app = document.getElementById('app');
  switch(state.view){
    case 'home': app.innerHTML = renderHome(); break;
    case 'colleges': app.innerHTML = renderColleges(); break;
    case 'majors': app.innerHTML = renderMajors(); break;
    case 'courses': app.innerHTML = renderCourses(); break;
  }
}

function renderHome(){
  return `
  <div class="hero fade-in">
    <h1>产业学院课程资源平台</h1>
    <p>覆盖9大产业学院，48门专业课程，线上线下同步学习</p>
    <div class="stats">
      <div class="stat"><div class="num">9</div><div class="label">产业学院</div></div>
      <div class="stat"><div class="num">9</div><div class="label">专业方向</div></div>
      <div class="stat"><div class="num">48</div><div class="label">精品课程</div></div>
    </div>
  </div>
  <div class="container">
    <div class="search-bar">
      <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <input type="text" placeholder="搜索课程、学院或专业方向..." oninput="onSearch(this.value)">
    </div>
    <h2 class="section-title">快速访问</h2>
    <div class="college-grid" id="college-grid-home"></div>
    <div class="admin-bar">管理后台：<a href="/admin/" style="color:var(--primary)">/admin/</a></div>
  </div>`;
}

function renderColleges(){
  return `<div class="container fade-in" style="padding-top:32px">
    <div class="list-header"><button class="back-btn" onclick="showHome()">← 返回</button><h2>全部学院</h2></div>
    <div class="college-grid" id="college-grid"></div>
  </div>`;
}

function renderMajors(){
  return `<div class="container fade-in" style="padding-top:32px">
    <div class="list-header">
      <button class="back-btn" onclick="showColleges()">← 返回</button>
      <h2>${escHtml(state.college?.name||'')}</h2>
    </div>
    <div class="major-grid" id="major-grid"></div>
  </div>`;
}

function renderCourses(){
  return `<div class="container fade-in" style="padding-top:32px">
    <div class="list-header">
      <button class="back-btn" onclick="showMajors(${state.collegeId})">← 返回专业</button>
      <h2>${escHtml(state.major?.name||'')}</h2>
    </div>
    <div class="course-list" id="course-list"></div>
  </div>`;
}

function escHtml(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}

function collegeIcon(name){
  const icons = {
    '数字技术':'💻','人工智能':'🤖','网络安全':'🔒','数字经济':'📈',
    '低空经济':'🚁','智能制造':'⚙️','智慧能源':'⚡','智慧文旅':'🎭','智慧康养':'🏥'
  };
  for(const k in icons)if(name.includes(k))return icons[k];
  return '🎓';
}

async function loadHome(){
  const colleges = await api('/colleges.php');
  state.colleges = colleges;
  const grid = document.getElementById('college-grid-home');
  if(grid){
    grid.innerHTML = colleges.map(c=>`
      <div class="college-card" onclick="showMajors(${c.id})">
        <div class="icon">${collegeIcon(c.name)}</div>
        <h3>${escHtml(c.name)}</h3>
        <p>${escHtml(colleges.find(x=>x.id===c.id)?.name||'')}</p>
        <div>
          <span class="badge">${c.major_count}个专业</span>
          <span class="badge">${c.course_count}门课程</span>
        </div>
        <div class="arrow">→</div>
      </div>`).join('');
  }
}

async function loadColleges(){
  const colleges = await api('/colleges.php');
  state.colleges = colleges;
  const grid = document.getElementById('college-grid');
  if(grid){
    grid.innerHTML = colleges.map(c=>`
      <div class="college-card" onclick="showMajors(${c.id})">
        <div class="icon">${collegeIcon(c.name)}</div>
        <h3>${escHtml(c.name)}</h3>
        <div>
          <span class="badge">${c.major_count}个专业</span>
          <span class="badge">${c.course_count}门课程</span>
        </div>
        <div class="arrow">→</div>
      </div>`).join('');
  }
}

async function showMajors(collegeId){
  state.view='majors';
  state.collegeId = collegeId;
  state.college = state.colleges.find(c=>c.id==collegeId) || {name:''};
  state.majorId = null;
  render();
  const majors = await api(`/majors.php?college_id=${collegeId}`);
  const grid = document.getElementById('major-grid');
  if(grid){
    grid.innerHTML = majors.map(m=>`
      <div class="major-card" onclick="showCourses(${collegeId},${m.id})">
        <h4>${escHtml(m.name)}</h4>
        <span>${m.course_count}门课程 →</span>
      </div>`).join('');
  }
}

async function showCourses(collegeId, majorId){
  state.view='courses';
  state.collegeId = collegeId;
  state.majorId = majorId;
  render();
  const courses = await api(`/courses.php?college_id=${collegeId}&major_id=${majorId}`);
  const list = document.getElementById('course-list');
  if(list){
    list.innerHTML = courses.map((c,i)=>`
      <div class="course-item" onclick="openCourse(${c.id})">
        <div class="info">
          <div class="num">${i+1}</div>
          <div>
            <h4>${escHtml(c.name)}</h4>
            <div class="meta">${escHtml(c.college_name)} · ${escHtml(c.major_name)}</div>
          </div>
        </div>
        <div class="arrow-right">→</div>
      </div>`).join('');
  }
}

async function openCourse(courseId){
  const courses = await api(`/courses.php?college_id=0&major_id=0`);
  const c = courses.find(x=>x.id==courseId);
  if(!c) return;

  const imgHtml = c.image_url
    ? `<img src="${escHtml(c.image_url)}" alt="${escHtml(c.name)}" onerror="this.parentElement.innerHTML='<div class=\\'placeholder\\'>🎓</div>'">`
    : `<div class="placeholder">🎓</div>`;

  const linksHtml = (c.online_url||c.offline_url)
    ? `<div class="links">
        ${c.online_url ? `<a class="link-btn online" href="${escHtml(c.online_url)}" target="_blank">🌐 线上课程</a>` : ''}
        ${c.offline_url ? `<a class="link-btn offline" href="${escHtml(c.offline_url)}" target="_blank">🏫 线下课程</a>` : ''}
       </div>`
    : `<div class="no-links">暂无课程地址，请联系管理员添加</div>`;

  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal">
      <div class="modal-header">
        <h2>${escHtml(c.name)}</h2>
        <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">×</button>
      </div>
      <div class="modal-body">
        <div class="course-img">${imgHtml}</div>
        ${c.description ? `<p class="desc">${escHtml(c.description)}</p>` : ''}
        ${linksHtml}
      </div>
    </div>`;
  modal.addEventListener('click', e=>{ if(e.target===modal) modal.remove(); });
  document.body.appendChild(modal);
}

function showHome(){ state.view='home'; state.majorId=null; state.major=null; render(); loadHome(); }
function showColleges(){ state.view='colleges'; render(); loadColleges(); }
function goAdmin(){ window.location.href='/admin/'; }
function onSearch(q){ if(!q) return showHome(); filterSearch(q); }

async function filterSearch(q){
  const courses = await api(`/courses.php?college_id=0&major_id=0`);
  const colleges = await api('/colleges.php');
  const filtered = courses.filter(c=>c.name.includes(q)||c.college_name.includes(q)||c.major_name.includes(q));
  state.view='courses';
  state.majorId=null;
  state.major={name:`搜索："${q}"`};
  render();
  const list = document.getElementById('course-list');
  if(list){
    if(filtered.length===0){
      list.innerHTML='<div style="text-align:center;padding:48px;color:var(--text-light)">未找到相关课程</div>';
    } else {
      list.innerHTML = filtered.map((c,i)=>`
        <div class="course-item" onclick="openCourse(${c.id})">
          <div class="info">
            <div class="num">${i+1}</div>
            <div>
              <h4>${escHtml(c.name)}</h4>
              <div class="meta">${escHtml(c.college_name)} · ${escHtml(c.major_name)}</div>
            </div>
          </div>
          <div class="arrow-right">→</div>
        </div>`).join('');
    }
  }
}

// init
document.getElementById('nav-home').addEventListener('click',e=>{e.preventDefault();showHome()});
document.getElementById('nav-colleges').addEventListener('click',e=>{e.preventDefault();showColleges()});
render();
loadHome();
</script>
</body>
</html>
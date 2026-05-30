CREATE TABLE IF NOT EXISTS colleges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE,
    icon TEXT DEFAULT 'school'
);

CREATE TABLE IF NOT EXISTS majors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    college_id INTEGER NOT NULL,
    FOREIGN KEY (college_id) REFERENCES colleges(id),
    UNIQUE(name, college_id)
);

CREATE TABLE IF NOT EXISTS courses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    major_id INTEGER NOT NULL,
    college_id INTEGER NOT NULL,
    image_url TEXT DEFAULT '',
    description TEXT DEFAULT '',
    online_url TEXT DEFAULT '',
    offline_url TEXT DEFAULT '',
    FOREIGN KEY (major_id) REFERENCES majors(id),
    FOREIGN KEY (college_id) REFERENCES colleges(id)
);

CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);
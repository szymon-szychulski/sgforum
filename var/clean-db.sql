set SQL_SAFE_UPDATES=0;
set collation_connection = 'utf8mb4_general_ci';

create database forum;

use forum;

alter database forum character set utf8mb4 collate utf8mb4_general_ci;

/* categories */

create table categories (
	cid			int unsigned primary key auto_increment,
	title 		varchar(255) not null,
	item 		tinyint unsigned not null
);

/* indexes of categories */

create index categoryItemIndex on categories (item);

/* forums */

create table forums (
	fid			int unsigned primary key auto_increment,
	cid 		int unsigned not null,
	item		tinyint unsigned not null,
	title		varchar(255) not null,
	lastPostId	int unsigned null default null,
	threadsNum	int unsigned not null default '0',
	postsNum	int unsigned not null default '0'
);

/* indexes of forums */

create index forumCategoryIndex on forums (cid, item);

/* foreing key */

/*
alter table forums
add constraint fk_cid
foreign key (cid) references categories(cid)
on update restrict
on delete restrict;

alter table forums drop foreign key fk_cid;
*/
      
/* threads */

create table threads (
	tid			int unsigned primary key auto_increment,
	fid			int unsigned not null,

	uid			int unsigned not null,
	title		varchar(255) not null,
	date 		datetime not null default current_timestamp,

	lastPostId	int unsigned not null,

	posts		int unsigned not null default '1',
	views		int unsigned not null default '0'
);

/* indexes of threads */

create index threadForumIndex on threads (fid);

/* posts */

create table posts (
	pid			int unsigned primary key auto_increment,
	tid			int unsigned not null,

	uid			int unsigned not null,
	date		datetime not null default current_timestamp,

	content 	text not null
);

/* indexes of posts */

create index postThreadIndex on posts (tid, date);

/* users */

create table users (
	uid			int unsigned primary key auto_increment,
	username 	varchar(16) not null,
	password	char(60) not null,
	email		varchar(120) not null,
	color		tinyint(2) unsigned not null,
	posts 		int unsigned not null default '0',
	token		char(40) null default null,
	activeKey	char(6) null default null,
	ban			date null default null
);

/* indexes of users */

create index userUsernameIndex on users (username);

create index userUsernameEmailIndex on users (username, email);


/* insert data */


insert into categories (title, item) values ('General discusion', 1);
insert into categories (title, item) values ('Learning', 2);
insert into categories (title, item) values ('Programming', 3);
insert into categories (title, item) values ('Others', 4);


insert into forums (cid, item, title) values(1, 1, 'News & Announces');
insert into forums (cid, item, title) values(1, 2, 'Issues and ideas');

insert into forums (cid, item, title) values(2, 1, 'Meetings');
insert into forums (cid, item, title) values(2, 2, 'Lessons');

insert into forums (cid, item, title) values(3, 1, 'HTML/CSS');
insert into forums (cid, item, title) values(3, 2, 'JavaScript');
insert into forums (cid, item, title) values(3, 3, 'PHP');
insert into forums (cid, item, title) values(3, 4, 'MySQL');
insert into forums (cid, item, title) values(3, 5, 'Other');

insert into forums (cid, item, title) values(4, 1, 'Computer technology');
insert into forums (cid, item, title) values(4, 2, 'Offtopic');
insert into forums (cid, item, title) values(4, 3, 'Trash');


/*
delete from categories;
truncate categories;

delete from forums;
truncate forums;
*/
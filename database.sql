drop table if exists _concurso;
CREATE TABLE _concurso(
	id int(11) auto_increment,
	end_date timestamp,
	title varchar(255),
	body text(0),
	winner1 varchar(255),
	winner2 varchar(255),
	winner3 varchar(255),
	prize1 varchar(255),
	prize2 varchar(255),
	prize3 varchar(255),
	primary key (id)
);

drop table if exists _concurso_images;
CREATE TABLE _concurso_images (
	id int(11) auto_increment,
	filename varchar(255),
	mime_type varchar(50),
	contest int(11) references _contest(id) on update cascade on delete cascade,
	primary key(id)
);
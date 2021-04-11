create table urls(
                     id integer not null primary key autoincrement,
                     url varchar(255)
);
create table links(
    id integer not null primary key autoincrement,
    url_id integer CHECK (links.url_id >0),
    shortLink varchar(12),
    foreign key (url_id) references urls(id)
);

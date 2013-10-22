create table users (
    user_id             int             primary key,
    screen_name         varchar(256)    not null,
    access_token        varchar(256)    not null,
    access_token_secret varchar(256)    not null,
    user_name           varchar(256),
    created             datetime,
    modified            datetime
);

create table stamprallies (
    stamprally_id       int             auto_increment primary key,
    stamprally_name     varchar(256)    not null,
    master_id           int             not null,
    master_name         varchar(256),
    place               varchar(256)    not null,
    lat                 double,
    lon                 double,
    description         text            not null,
    start_date          datetime        not null,
    end_date            datetime        not null,
    created             datetime,
    modified            datetime,
    
    foreign key(master_id) references users(user_id)
);

create table participants (
    user_id             int,
    stamprally_id       int,
    created             datetime,
    modified            datetime,
    
    primary key(user_id, stamprally_id),
    foreign key(user_id) references users(user_id),
    foreign key(stamprally_id) references stamprallies(stamprally_id)
);

create table tickets (
    ticket_id           int             auto_increment primary key,
    stamprally_id       int             not null,
    ticket_name         varchar(256)    not null,
    description         text,
    limit_date          datetime        not null,
    required_checkpoint_num int         not null,
    limit_ticket_num    int             not null,
    type                enum('food', 'shopping', 'gift'),
    created             datetime,
    modified            datetime,
    
    foreign key(stamprally_id) references stamprallies(stamprally_id)
);

create table checkpoints (
    checkpoint_id       int             auto_increment primary key,
    checkpoint_name     varchar(256)    not null,
    public_description  text            not null,
    private_description text,
    stamprally_id       int             not null,
    url                 varchar(256)    not null,
    created             datetime,
    modified            datetime,
    
    foreign key(stamprally_id) references stamprallies(stamprally_id)
);

create table checked_checkpoints (
    user_id             int,
    checkpoint_id       int,
    checked_date        datetime        not null,
    created             datetime,
    modified            datetime,
    
    primary key(user_id, checkpoint_id),
    foreign key(user_id) references users(user_id),
    foreign key(checkpoint_id) references checkpoints(checkpoint_id)
);

create table got_tickets (
    user_id             int,
    ticket_id           int,
    exchanged_ticket    boolean         not null,
    created             datetime,
    modified            datetime,
    
    primary key(user_id, ticket_id),
    foreign key(user_id) references users(user_id),
    foreign key(ticket_id) references tickets(ticket_id)
);


// 以下、サンプルデータ


insert into users (user_id, screen_name, access_token, access_token_secret, user_name, created, modified) values
(127982310, 'niboshiporipori', '127982310-ydV1nZWh9ksxYozJVnCriMwqmolJ93ViQNDPYVQo', 'zWsPitwRCg1GJThqDgstlN9srYgYrz4q0aJSPiXdH4', '松村裕次郎', now(), now());

insert into stamprallies (stamprally_name, master_id, master_name, place, lat, lon, description, start_date, end_date, created, modified) values
('わかやまウォーク', 127982310, '和歌山大学教養学部肉体サイエンス学科', '和歌山城公園', 34.228764, 135.172341,
 "和城公園をウォーキングしまくって地域を活性化させよう！　和歌山を歩きつくせ！！ \nぶらくり丁福引券等がもらえるよ。",
 '2013-11-01 12:00:00', '2014-05-01 00:00:00', now(), now()),
('大学見学会',       127982310, '和歌山大学・商工会議所',               '和歌山大学',   34.266509, 135.151742,
 "商工会議所の皆様向けに、和歌山大学の見学会を行います。\n現地でスタンプラリーも実施しますので、ぜひお試しください。",
 '2013-12-08 10:00:00', '2013-12-08 17:00:00', now(), now());

insert into participants (user_id, stamprally_id, created, modified) values
(127982310, 1, now(), now()),
(127982310, 2, now(), now());

insert into tickets (stamprally_id, ticket_name, description, limit_date, required_checkpoint_num, limit_ticket_num, created, modified) values
(1, 'ぶらくり丁福引券',       "1回福引を回せます。",
 '2014-06-01 00:00:00', 1, 300, 'shopping', now(), now()),
(1, 'ぶらくり丁福引券',       "1回福引を回せます。",
 '2014-06-01 00:00:00', 2, 300, 'shopping', now(), now()),
(1, 'ぶらくり丁福引券',       "1回福引を回せます。",
 '2014-06-01 00:00:00', 3, 200, 'shopping', now(), now()),
(1, '割引券',                 "すべての商品を2割引きで購入できます。",
 '2014-06-01 00:00:00', 3,  50, 'shopping', now(), now()),
(1, 'ぶらくり丁福引券',       "1回福引を回せます。",
 '2014-06-01 00:00:00', 4, 200, 'shopping', now(), now()),
(1, 'ぶらくり丁福引券',       "1回福引を回せます。",
 '2014-06-01 00:00:00', 5, 100, 'shopping', now(), now()),
(1, 'ぶらっくりー人形',       "ぶらっくりー人形がもらえます。",
 '2014-06-01 00:00:00', 5,  50, 'gift', now(), now()),
(2, 'わだにゃんキーホルダー', "システム工学部で開発した猫型ロボットのキーホルダーです",
 '2013-12-08 23:59:59', 3,  35, 'gitf', now(), now());

insert into checkpoints (checkpoint_name, public_description, private_description, stamprally_id, url, created, modified) values
('和歌山公園動物園', "和歌山の隠れスポット", "パンダがいると思った？　残念！", 1, 'http://example.com', now(), now()),
('和歌山城一の丸',   "一の丸",               "みんな知ってるよね！",           1, 'http://example.com', now(), now()),
('和歌山城二の丸',   "二の丸",               "遠い",                           1, 'http://example.com', now(), now()),
('和歌山城天守閣',   "天守閣",               "待ち合わせに最適！！",           1, 'http://example.com', now(), now()),
('和歌山市役所',     "市役所入口",           "県庁じゃないよ！",               1, 'http://example.com', now(), now()),
('システム工学部',   "A棟1階教務課横にあります", "システム工学部では多くの優秀な人材を輩出しています。",
 2, 'http://example.com', now(), now()),
('経済学部',         "経済学部入口の掲示板にあります", "当大学で最も伝統のある学部です。",
 2, 'http://example.com', now(), now()),
('図書館',           "図書館入口に設置しています", "当大学の図書館は一般の方もご利用できます。",
 2, 'http://example.com', now(), now());

insert into checked_checkpoints (user_id, checkpoint_id, checked_date, created, modified) values
(127982310, 1, '2013-11-01 17:24:29', now(), now()),
(127982310, 3, '2013-11-12 10:01:34', now(), now()),
(127982310, 2, '2013-12-03 09:21:41', now(), now()),
(127982310, 8, '2013-12-08 10:11:13', now(), now()),
(127982310, 7, '2013-12-08 12:32:08', now(), now()),
(127982310, 6, '2013-12-08 14:47:19', now(), now());

insert into got_tickets (user_id, ticket_id, exchanged_ticket, created, modified) values
(127982310, 1,  TRUE, now(), now()),
(127982310, 2,  TRUE, now(), now()),
(127982310, 3, FALSE, now(), now()),
(127982310, 4,  TRUE, now(), now()),
(127982310, 8, FALSE, now(), now());


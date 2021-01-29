create table faceit_players
(
    id serial not null,
    faceit_id varchar(255) not null,
    nickname varchar(255) not null,
    avatar varchar(255) not null,
    skill_level int not null,
    faceit_elo int not null,
    faceit_url varchar(255) not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_players_faceit_id_uindex
    on faceit_players (faceit_id);

create unique index faceit_players_id_uindex
    on faceit_players (id);

alter table faceit_players
    add constraint faceit_players_pk
        primary key (id);

create table faceit_matches
(
    id serial not null,
    faceit_id varchar(255) not null,
    game_mode varchar(255) not null,
    competition_type varchar(255) not null,
    status varchar(255) not null,
    map varchar(255) not null,
    rounds int not null,
    score varchar(255) not null,
    faceit_url varchar(255),
    started_at timestamp not null,
    finished_at timestamp not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_faceit_id_uindex
    on faceit_matches (faceit_id);

create unique index faceit_matches_id_uindex
    on faceit_matches (id);

alter table faceit_matches
    add constraint faceit_matches_pk
        primary key (id);

create table faceit_matches_teams
(
    id serial not null,
    faceit_matches_id int not null
        constraint faceit_matches_teams_faceit_matches_id_fk
            references faceit_matches
            on delete cascade,
    name varchar(255) not null,
    is_win boolean not null,
    first_half_rounds int not null,
    second_half_rounds int not null,
    overtime_rounds int not null,
    final_rounds int not null,
    headshots_percentage float not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_teams_id_uindex
    on faceit_matches_teams (id);

alter table faceit_matches_teams
    add constraint faceit_matches_teams_pk
        primary key (id);

create table faceit_matches_teams_players
(
    id serial not null,
    faceit_matches_teams_id int not null
        constraint faceit_matches_teams_players_faceit_matches_teams_id_fk
            references faceit_matches_teams
            on delete cascade,
    faceit_player_id varchar(255) not null,
    nickname varchar(255) not null,
    kills int not null,
    deaths int not null,
    assists int not null,
    headshots int not null,
    headshots_percentage float not null,
    triple_kills int not null,
    quadro_kills int not null,
    penta_kills int not null,
    mvps int not null,
    kd_ratio float not null,
    kr_ratio float not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_matches_teams_players_id_uindex
    on faceit_matches_teams_players (id);

alter table faceit_matches_teams_players
    add constraint faceit_matches_teams_players_pk
        primary key (id);

create table faceit_players_statistics
(
    id serial not null,
    faceit_player_id varchar(255) not null,
    type varchar(255) not null,
    matches int not null,
    wins int not null,
    win_rate float not null,
    kd_ratio float not null,
    average_kd_ratio float not null,
    headshots int not null,
    average_headshots int not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index faceit_players_statistics_id_uindex
    on faceit_players_statistics (id);

create unique index faceit_players_statistics_faceit_players_id_type_uindex
    on faceit_players_statistics (faceit_player_id, type);

alter table faceit_players_statistics
    add constraint faceit_players_statistics_pk
        primary key (id);

create table faceit_players_statistics_segments
(
    id serial not null
        constraint faceit_players_statistics_segments_pk
            primary key,
    faceit_players_statistics_id integer not null
        constraint faceit_players_statistics_segments_fps_id_fk
            references faceit_players_statistics
            on delete cascade,
    type varchar(255) not null,
    mode varchar(255) not null,
    label varchar(255) not null,
    image varchar(255) not null,
    kills integer not null,
    average_kills double precision not null,
    assists integer not null,
    average_assists double precision not null,
    deaths integer not null,
    average_deaths double precision not null,
    headshots integer not null,
    total_headshots integer not null,
    average_headshots double precision not null,
    headshots_per_match double precision not null,
    kr_ratio double precision not null,
    average_kr_ratio double precision not null,
    kd_ratio double precision not null,
    average_kd_ratio double precision not null,
    triple_kills integer not null,
    quadro_kills integer not null,
    penta_kills integer not null,
    average_triple_kills double precision not null,
    average_quadro_kills double precision not null,
    average_penta_kills double precision not null,
    mvps integer not null,
    average_mvps double precision not null,
    matches integer not null,
    rounds integer not null,
    wins integer not null,
    win_rate double precision not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

alter table faceit_players_statistics_segments owner to "default";

create unique index faceit_players_statistics_segments_id_uindex
    on faceit_players_statistics_segments (id);

create unique index faceit_players_statistics_segments_fps_id_label_uindex
    on faceit_players_statistics_segments (faceit_players_statistics_id, label, mode);
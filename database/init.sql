create table training_maps
(
    id serial not null,
    name varchar(255) not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index training_maps_id_uindex
    on training_maps (id);

create unique index training_maps_name_uindex
    on training_maps (name);

alter table training_maps
    add constraint training_maps_pk
        primary key (id);

CREATE OR REPLACE FUNCTION set_update_at_now_on_training_maps_table()
    RETURNS TRIGGER
    LANGUAGE PLPGSQL
AS
$$
BEGIN
    NEW.updated_at = now();

    RETURN NEW;
END;
$$;

CREATE TRIGGER update_training_maps_table
    BEFORE update
    ON training_maps
    FOR EACH ROW
EXECUTE PROCEDURE set_update_at_now_on_training_maps_table();

create table trainings
(
    id serial not null,
    date timestamp not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index trainings_id_uindex
    on trainings (id);

alter table trainings
    add constraint trainings_pk
        primary key (id);

CREATE OR REPLACE FUNCTION set_update_at_now_on_trainings_table()
    RETURNS TRIGGER
    LANGUAGE PLPGSQL
AS
$$
BEGIN
    NEW.updated_at = now();

    RETURN NEW;
END;
$$;

CREATE TRIGGER update_trainings_table
    BEFORE update
    ON trainings
    FOR EACH ROW
EXECUTE PROCEDURE set_update_at_now_on_trainings_table();

CREATE TYPE training_mode AS ENUM ('kill', 'minute');

create table training_parts
(
    id serial not null,
    map_id int not null
        constraint training_parts_training_maps_id_fk
            references training_maps
            on delete cascade,
    training_id int not null
        constraint training_parts_trainings_id_fk
            references trainings
            on delete cascade,
    name varchar(255) not null,
    is_ended bool not null,
    mode training_mode not null,
    value int not null,
    created_at timestamp default now() not null,
    updated_at timestamp
);

create unique index training_parts_id_uindex
    on training_parts (id);

alter table training_parts
    add constraint training_parts_pk
        primary key (id);

CREATE OR REPLACE FUNCTION set_update_at_now_on_training_parts_table()
    RETURNS TRIGGER
    LANGUAGE PLPGSQL
AS
$$
BEGIN
    NEW.updated_at = now();

    RETURN NEW;
END;
$$;

CREATE TRIGGER update_training_parts_table
    BEFORE update
    ON training_parts
    FOR EACH ROW
EXECUTE PROCEDURE set_update_at_now_on_training_parts_table();



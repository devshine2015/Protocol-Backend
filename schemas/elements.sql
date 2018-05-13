CREATE TABLE elements (
  "id" INT PRIMARY KEY GENERATED BY DEFAULT AS IDENTITY (START WITH 1013 INCREMENT BY 97),
  "type" SMALLINT NOT NULL,
  "url" VARCHAR(2083) NOT NULL,
  "start_locator" VARCHAR(200),
  "start_offset" INT,
  "end_locator" VARCHAR(200),
  "end_offset" INT,
  "image" VARCHAR(200),
  "text" TEXT,
  "rect" VARCHAR(100),
  "created_at" TIMESTAMP NOT NULL,
  "created_by" INT,
  "updated_at" TIMESTAMP NOT NULL,
  "updated_by" INT,
  "status" SMALLINT DEFAULT 0
);

CREATE INDEX elements_url_idx on elements ("url");

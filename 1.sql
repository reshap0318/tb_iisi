﻿SELECT id, ST_X(ST_Centroid(geom)) AS longitude, ST_Y(ST_CENTROID(geom)) As latitude from mesjid  where id not in (41)
-- Продано за месяц
CREATE VIEW orders as SELECT TO_CHAR(to_timestamp("created_at")::date, 'mm') as month,
SUM(price) as price FROM "order"
WHERE status = 1
GROUP BY "month"
ORDER BY month asc
LIMIT 1;

-- Обшие долги

<!--CREATE VIEW total_debts as-->
<!--SELECT SUM(amount) as amount from "balans"-->
<!--WHERE status = 1;-->


CREATE VIEW balans_coming as
SELECT SUM(amount) as sum from "balans"
WHERE income_outgo = 0 and status = 0;


CREATE VIEW balans_outgo as
SELECT SUM(amount) as sum from "balans"
WHERE income_outgo = 1  and status = 0;


-- story
CREATE VIEW store as
select COUNT(username) as storeCount
from "user"
LEFT JOIN auth_assignment ON user_id::int = "user"."id"
WHERE auth_assignment.item_name = 'story';

-- Товаров складе
CREATE VIEW store_coming as
SELECT sum(count) from product
LEFT JOIN "user" on product.user_id = "user".id
LEFT JOIN auth_assignment ON auth_assignment.user_id::int = "user"."id"
WHERE auth_assignment.item_name= 'story' and
product.coming_outgo = 0;

CREATE VIEW store_outgo as
SELECT sum(count) from product
LEFT JOIN "user" on product.user_id = "user".id
LEFT JOIN auth_assignment ON auth_assignment.user_id::int = "user"."id"
WHERE auth_assignment.item_name= 'story' and
product.coming_outgo = 1;

-- diller

CREATE VIEW diller as
select COUNT(username) as dillerCount
from "user"
LEFT JOIN auth_assignment ON user_id::int = "user"."id"
WHERE auth_assignment.item_name = 'diller';

-- market

CREATE VIEW market as
select COUNT(username) as marketCount
from "user"
LEFT JOIN auth_assignment ON user_id::int = "user"."id"
WHERE auth_assignment.item_name = 'market';

-- client
CREATE VIEW client as
select COUNT(username) as clientCount
from "user"
LEFT JOIN auth_assignment ON user_id::int = "user"."id"
WHERE auth_assignment.item_name = 'client';

-- Количество предзаказов

CREATE VIEW preorder as
SELECT COUNT(*) from "order"
WHERE status = 3;





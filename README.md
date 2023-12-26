# Documentation
## Rapid Crew API
This api is designed to serve applications with the latest tech devices, grant access to the rapid crew's market place, gather information about application usage, and facilitate rapid business transactions in the tech community. It stands as a bridge between Rapid Crew's user interface and its backend infrastructure.

## FILTERING
Some endpoints provide you with a paginated list of resources.

To filter the list you have to use the following format

`[]=<operator>`

This format has to be attached to the endpoint and a url query e.g

`https://rapidcrew.com/api/categories?name[in]=computer+accessories`

Using the format explained above multiple times would result in an "AND" query.

Here is a list of possible translations:

| URL | TRANSLATION |
| --- | --- |
| `name[in]=computer+accessories` | `select * from categories where name like "%computer accessories%"` |
| `name[in]=computer+accessories&age[eq]=50` | `select * from categories where name like "%computer accessories%" and age = 50` |

## Category
Includes description about how to use the rapid crew api to perform CRUD operations on our product categories

### GET
#### Read
`http://192.168.43.151:8000/api/categories?subs=true`

Here is the list of accepted filter parameters and their operators for the categories endpoint.

| Filter Parameters | Operators Allowed |
| --- | --- |
| name | `[in]` , `[eq]` |

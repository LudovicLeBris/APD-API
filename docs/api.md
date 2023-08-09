# API documentation

| Number |  Endpoint | HTTP method | Request Datas | Response Datas | Description |
|--|--|--|--|--|--|
| 1 | /api/ductdimension | GET | shape, flowRate, width, flowSpeed (optionnal) | diameter or width and height | Retrieve the rigth diameter or rectangular dimensions with a 7m/s flow speed or other |
| 2 | /api/ductsection | GET | shape, diameter or width and height | ductsection | Retrieve the duct section |
| 3 | /api/flowSpeed | GET | flowRate, shape, diameter or width and height | flowSpeed | Retrieve the flow speed |
| 4 | /api/section | GET | shape, material, diameter or width and height, flowRate, length, singularities, additionalApd, temperature (optionnal), altitude (optionnal) | ductSection, flowSpeed, linearApd, singularApd, additionalApd, totalApd | Retrieve all calculation for a duct section |
| 5 | /api/sections | GET | datas for all duct sections (shape, material, diameter or width and height, flowRate, length, singularities, additionalApd) + generalAdditionalApd, temperature (optionnal), altitude (optionnal) | totalLinearApd, totalSingularApd, totalAdditionalApd, totalApd | Retrieve all calculation for all duct sections |
| 6 | /api/diameters | GET | - | diameters | Retrieve all stored diameters |
| 7 | /api/materials | GET | - | materials name | Retrieve all stored materials |
| 8 | /api/singularities/[shape] | GET | - | singularities | Retrieve all stored singularities by shape |
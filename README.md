# 

## Project setup
```
docker-compose up -d
docker-compose exec node npm install
composer install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```
## Features

1. **Transaction Upload**: Upload a list of transactions from an Excel file. The data is saved to a MySQL table on the server, and old data is deleted.
2. **Currency Rates**: Display currency exchange rates for CHF used in transactions.
3. **Bank Account Management**: List bank accounts used in transactions. Users can edit account names and initial balances directly in the table. The final balance is calculated automatically.
4. **Balance Charts**: Display charts reflecting balance changes on all accounts using the transaction table. Charts can be shown/hidden and downloaded in PNG/PDF/SVG formats.
5. **Transaction Editing**: Edit the list of transactions. The current list can be downloaded in PDF or Excel formats.

## Technologies Used

- **Backend**: PHP5.6
- **Database**: MySQL5.7
- **Frontend**: HTML5, CSS3, jQuery, Vue2
- **Charting**: Highcharts JS
- **Data Management**: DataTables Editor

import React from 'react'
import Table from '../components/Table'
import FilterComponent from '../components/FilterComponent'


const head = ['Date', 'Amount', 'Month', 'Means', 'Year' ];
const data = [
    {'date': '2024-09-09', 'month': 'September', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'March', 'means': '+256790461256', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'April', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'January', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'December', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'October', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'June', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'August', 'means': '0789651678', 'amount':'800000', 'year':'2024'},
    {'date': '2024-09-09', 'month': 'November', 'means': '+256789651678', 'amount':'800000', 'year':'2025'}
];

const filterYear = [
    {"value": "2024", "label": "2024"},
    {"value": "2023", "label": "2023"},
    {"value": "2022", "label": "2022"},
    {"value": "2021", "label": "2021"},
]
    

const Contributions = ()=>{
    return (
        <div className='contribution-container'>
            <div className="wrapper">
                <div className="members-section">
                    <div className="members">
                        <ul>
                            <li className='active-member'>All</li>
                            <li>Ssentamu Abel</li>
                            <li>Musigunzi Arafat</li>
                            <li>Ssentamu Abel</li>
                            <li>Musigunzi Arafat</li>
                            <li>Ssentamu Abel</li>
                            <li>Musigunzi Arafat</li>
                            
                        </ul>
                    </div>
                </div>
                <div className="trans-section">
                    <div className="filter">
                        <FilterComponent options={filterYear} name="year" id="year" />
                    </div>
                    <div className="table">
                        <Table col={head} data={data}/>
                    </div>
                </div>
            </div>           
        </div>
    )
}

export default Contributions;
import React, { useEffect, useState } from 'react'
import Table from '../components/Table'
import FilterComponent from '../components/Select'
import FilterDate from '../components/Input';


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

const filterMonth = [
    {"value":"1", "label":"January"},
    {"value":"2", "label":"February"},
    {"value":"3", "label":"March"},
    {"value":"4", "label":"April"},
    {"value":"5", "label":"May"},
    {"value":"6", "label":"June"},
    {"value":"7", "label":"July"},
    {"value":"8", "label":"August"},
]
    

const Contributions = ()=>{

    const [contributors, setContributors] = useState([]);
    const [datas, setDatas] = useState([]);

    useEffect(()=>{
        const fetchdata = async ()=>{
            try {
                const response = await fetch('http://127.0.0.1:8000/api/contributions');

                
            } catch (error) {
                console.log(error);
            }
            


        }
        
       fetchdata();
    }, [])

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
                        {/* <FilterDate type="date" name="date" id="date" width='20%' /> */}
                        {/* <FilterComponent options={filterMonth} name="month" id="month"   />
                        <FilterComponent options={filterYear} name="year" id="year"  />
                        */}
                       
                       
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
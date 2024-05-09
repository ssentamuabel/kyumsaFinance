import React from 'react'


const Table = ({col, data})=>{

    const head = col.map((item, index)=>(
        <th key={index}>{item}</th>    

    )) 

    const body = data.map((item, index)=>(
        <tr key={index}>
            <td>{item.date}</td>
            <td>{item.amount}</td>
            <td>{item.month}</td>
            <td>{item.means}</td>
            <td>{item.year}</td>
        </tr>
    ))

    return (
        <div className=".tabular--wrapper">
            <table>
                <thead>

                    <tr>
                        {head}
                    </tr>
                </thead>
                <tbody >
                    {body}
                </tbody>
                <tfoot>
                    <tr>
                        <td colSpan="7">Total: $3,800</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    )
}

export default Table;
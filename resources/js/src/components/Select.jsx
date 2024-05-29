import React from 'react'


const Select = ({options, ...props})=>{

    
    const opt = options.map((item, key) =>(
        <option key ={key} value={item.value}>{item.label}</option>
    ))

    return (
        <select  name="props.name" id="props.id">
            
            {opt}
           
        </select>
    )
}








export default Select
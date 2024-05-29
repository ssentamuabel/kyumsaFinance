import React from 'react'

const Button = (props)=>{

    const style ={
        border: 'none',
        color: '#fff',
        textAlign: 'center',
        textDecoration: 'none',
        background:props.fill,
        
    }

    return (
        <button style={style} type={props.type} {...props}>{props.text}</button>
    )
}

export default Button;
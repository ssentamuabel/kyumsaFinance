import React from 'react'

const Header = ()=>{
    return(
        <div style={Styles.container}>
            <div style={Styles.title} className="left-section">
                Alunmni Kyumsa Financial Support Initiative
            </div>
            <div style={Styles.right} className="right-section">
                <div className="user" style={Styles.user}>Ssentamu Abel</div>
                <div className="log-out" style={Styles.logout}>
                    logout
                </div>
            </div>
        </div>
    )
}


const Styles = {
    container : {
        background: "#0b9954",
        width: "99%",
        padding: "10px",
        margin: '10px',       
        display: "flex",
        justifyContent:"space-between",
        borderRadius: "5px",
        textAlign:'center',
        color:'#fff',
        border: '1px solid #18392B'
       
    },
    right: {
        display: 'flex',
        fontSize: '1em',
        alignItems: 'center'
    },
    user:{
        marginLeft: '25px',
        color:'#fff',
    },
    logout : {
        marginLeft: '25px',
        color:'#fff',
    },
    title:{
        fontSize: '1.5em',
        fontWeight: 'bold',
        letterSpacing: '2px'
    }

   
}

export default Header
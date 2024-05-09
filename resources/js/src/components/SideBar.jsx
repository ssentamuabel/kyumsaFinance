import React from 'react'
import { NavLink } from 'react-router-dom'
import { 
    FaTh,
    FaMoneyBillWave,
    FaMailBulk,
    FaUsers,
    FaBars,
    FaMosque


} from 'react-icons/fa'

const SideBar = ({children})=>{

    const menuItem = [
        {
            "path" : "/",
            "name" : "Dashboard",
            "icon" :<FaTh/>
        },
        {
            "path" : "/contributions",
            "name" : "Contributions",
            "icon" :<FaMoneyBillWave />
        },
        {
            path : "/sms",
            name : "Messages",
            icon : <FaMailBulk />
        },
        {
            path : "/users",
            name : "Users",
            icon : <FaUsers />
        }
    ]
    return (
        <div className='container'>
            <div className="sidebar">
                <div className="top-section">
                    <div className="logo"><FaMosque/></div>
                    <h1 className="title">AKFSI</h1>
                   
                </div>
                <div className='nav'>
                {
                    menuItem.map((item, index)=>(
                        <NavLink  to={item.path} key={index} className='link'   >
                            <div className="icon">{item.icon}</div>
                            <div className="link-text">{item.name}</div>
                        </NavLink>
                    ))
                }

                </div>
                
            </div>
            <main>{children}</main>
        </div>
    )
}

export default SideBar
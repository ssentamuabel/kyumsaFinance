import React, { useEffect } from 'react'

import Button from '../components/Button';
import TextArea from '../components/TextArea';


const Sms = ()=>{

    const sendReminder = async () => {
        try {
            const response = await fetch('http://127.0.0.1:8000/api/contributions/notification');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const result = await response.json();
            console.log(result.response.data.SMSMessageData);
        } catch (error) {
            console.log('Error sending reminder:', error);
        }
    };

    return (
        <div className='sms-container'>
            <div className="wrapper">
                <div className="sms-right">
                    <div className="reminder">
                        <Button  
                            type="button" 
                            text="Reminder" 
                            fill="blue" 
                            id="large-button"
                            onClick={sendReminder}
                        />
                        <p id="tooltip-text">Send a reminder for this month</p>
                        
                    </div>
                    <div className="mass-message">
                        <div className="message">
                            {/* <TextArea /> */}
                        </div>
                    </div>
                    
                </div>
                <div className="sms-left">
                    sms-left
                </div>

            </div>            
        </div>
    )
}

export default Sms;
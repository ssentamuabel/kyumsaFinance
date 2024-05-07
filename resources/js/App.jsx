import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import SideBar from './src/components/SideBar';
import Dashboard from './src/Pages/Dashboard';
import Contributions from './src/Pages/Contributions';
import Sms from './src/Pages/Sms';
import Users from './src/Pages/Users';

function App() {
    return (
        <SideBar>
            <Routes>
                <Route path='/' element={<Dashboard/>} />
                <Route path='/contributions' element={<Contributions/>} />
                <Route path='/sms' element={<Sms/>} />
                <Route path='/users' element={<Users />} />
            </Routes>
      </SideBar>
    );
}

export default App;

if (document.getElementById('example')) {
    const Index = ReactDOM.createRoot(document.getElementById("example"));

    Index.render(
        <React.StrictMode>
            <BrowserRouter>                
                <App />
            </BrowserRouter>           
        </React.StrictMode>
    )
}

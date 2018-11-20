import React, { Component } from 'react';
import { Switch, Route } from 'react-router-dom'

import './App.css';
import MessagesList from "./components/MessagesList";
import Navbar from "./components/navbar";
import Welcome from "./components/Welcome";
import SearchMessageList from "./components/SearchMessageList";
import Login from "./components/Login";

class App extends Component {
  render() {
    return (
      <div className="mdl-layout mdl-js-layout mdl-layout--fixed-header">
          <Navbar/>
          <main className="mdl-layout__content">
            <Switch>
                <Route exact path='/' component={Welcome}/>
                <Route path='/messages' component={MessagesList} />
                <Route path="/search" component={SearchMessageList}/>
                <Route path="/login" component={Login}/>
            </Switch>
          </main>
      </div>
    );
  }
}

export default App;

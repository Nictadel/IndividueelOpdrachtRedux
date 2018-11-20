import React, { Component, Fragment } from 'react';
import { Redirect } from 'react-router-dom';

import { connect } from "react-redux";
import { bindActionCreators } from "redux";
import { storeUserName } from "../actions/userInfoAction";

class Login extends Component {
    constructor(props) {
        super(props);

        this.state = {
            name: "",
            navigate: false,
            password: false,
            invalidUsername: false,
            invalidPassword: false
        };
    }

    onClick = () => {
        if (!this.state.invalidUsername && this.state.password) {
            this.props.storeUserName(this.state.name);
            this.setState({navigate: true})
        }
    };

    passwordChange(password) {
        let newPasswordState;

        if (password != null) {
            newPasswordState = true;
        } else {
            newPasswordState = false;
        }

        this.setState({ password: newPasswordState, invalidPassword: !newPasswordState });
    };
    changeUsername(username) {
        let helpUsername = false;
        if (username === "Jorne" || username === "Bene" || username === "Ferre") {
            helpUsername = false;
        } else {
            helpUsername = true;
        }
        this.setState({ name: username, invalidUsername: helpUsername})
    }

    render() {
        return (
            this.state.navigate ? <Redirect to="/messages" /> :
                <Fragment>
                    <h1 className="mdl-cell--4-offset mdl-cell--4-col">
                        Login
                    </h1>
                    <form className="mdl-cell--4-offset mdl-cell--4-col">
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input
                                className="mdl-textfield__input"
                                type="text"
                                id="username"
                                value={this.state.name}
                                onChange={(e) => this.changeUsername(e.target.value)}
                            />
                            <label className="mdl-textfield__label" htmlFor="username">Gebruikersnaam</label>
                        </div>
                        {this.state.invalidUsername ?
                        <div>
                            <label>Vul een geldige gebruikersnaam in a.u.b.</label>
                        </div>:null
                        }
                        <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input className="mdl-textfield__input"
                                   type="password"
                                   id="sample3"
                                   onChange={(e) => this.passwordChange(e.target.value)}
                            />
                            <label className="mdl-textfield__label" htmlFor="sample3">Wachtwoord</label>
                        </div>
                        {this.state.invalidPassword ?
                        <div>
                            <label>Vul een geldig wachtwoord in a.u.b.</label>
                        </div>:null}
                        <div>
                            <button
                                className="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect"
                                onClick={this.onClick}
                                disabled={!this.state.name || !this.state.password}>
                                Login
                            </button>
                        </div>
                    </form>
                </Fragment>
        );
    }
}

function mapStateToProps() {
    return {};
}

function mapDispatchToProps(dispatch) {
    return {
        storeUserName: bindActionCreators(storeUserName, dispatch)
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(Login);

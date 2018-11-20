import {Fragment} from "react";
import React from "react";
import PropTypes from "prop-types";
import { connect } from "react-redux";
import {Link} from "react-router-dom";

const Navbar = (props) => {

    return (
        <Fragment>
            <header className="mdl-layout__header">
                <div className="mdl-layout__header-row">
                    <Link className="mdl-navigation__link" to="/">
                        <span className="mdl-layout-title">Forum</span>
                    </Link>
                    <div className="mdl-layout-spacer"></div>
                    <nav className="mdl-navigation mdl-layout--large-screen-only">
                        <Link className="mdl-navigation__link" to="/messages">Alle berichten</Link>
                        <Link className="mdl-navigation__link" to="/search">Zoek een bericht</Link>
                        <Link className="mdl-navigation__link" to="/login">{props.username !== '' ? props.username : "Login"}</Link>
                    </nav>
                </div>
            </header>
            <div className="mdl-layout__drawer">
                <span className="mdl-layout-title">Forum</span>
                <nav className="mdl-navigation">
                    <a className="mdl-navigation__link" href="/messages">Alle berichten</a>
                    <a className="mdl-navigation__link" href="/search">Zoek een bericht</a>
                    <a className="mdl-navigation__link" href="/login">{props.username !== '' ? props.username : "Login"}</a>
                </nav>
            </div>
        </Fragment>
    )
}

Navbar.propTypes = {
    username: PropTypes.string
}

function mapStateToProps(state) {
    return {
        username: state.userInfo.name
    };
}

function mapDispatchToProps() {
    return {};
}

export default connect(mapStateToProps, mapDispatchToProps)(Navbar);

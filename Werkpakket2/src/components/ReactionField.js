import {Fragment} from "react";
import React from "react";
import PropTypes from "prop-types";

const ReactionField = (props) => {
    return (
        <Fragment>
            <div className="mdl-cell mdl-cell--12-col mdl-textfield mdl-js-textfield">
                <textarea className="mdl-textfield__input" type="text" rows="3" id="sample5"
                          onChange={props.updateReaction}
                          placeholder="Plaats uw reactie hier..."
                ></textarea>
                <label className="mdl-textfield__label" htmlFor="sample5"></label>
            </div>
            <button className="mdl-button mdl-js-button mdl-js-ripple-effect" onClick={props.createReaction}>Plaats Reactie</button>
        </Fragment>
    );
}

ReactionField.propTypes = {
    updateReaction: PropTypes.func,
    createReaction: PropTypes.func
}

export default ReactionField;
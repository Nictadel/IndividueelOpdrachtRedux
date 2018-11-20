import React from 'react';
import PropTypes from "prop-types";
import ReactionField from "./ReactionField";

const Message = (props) => {
    return (
        <div className="mdl-cell--4-col">
            <div className="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col">
                <div className="mdl-card__title">
                    <h3 className="mdl-cell--1-offset">Bericht {props.message.Id}</h3>
                </div>
                <div className="mdl-card__supporting-text mdl-cell--1-offset">
                    <h6>Inhoud:</h6>
                    <p>{props.message.Content}</p>
                    <p>Categorie: {props.message.Category}</p>
                </div>
                <div className="mdl-card__actions mdl-card--border">
                    <span className="mdl-cell mdl-cell--6-col">
                        <button className="mdl-button mdl-js-button mdl-button--icon" onClick={props.upvoteMessage}>
                            <i className="material-icons">
                                thumb_up_alt
                            </i>
                        </button>
                        {props.message.Upvotes}
                    </span>
                    <span className="mdl-cell mdl-cell--6-col">
                    <button className="mdl-button mdl-js-button mdl-button--icon" onClick={props.downvoteMessage}>
                        <i className="material-icons">
                            thumb_down_alt
                        </i>
                    </button>
                        {props.message.Downvotes}
                    </span>
                    <span className="mdl-cell--1-offset">
                        <button
                            className="mdl-button mdl-js-button mdl-js-ripple-effect"
                            onClick={props.changeReactionFieldState}
                        >Reageer</button>
                    </span>
                </div>
                {props.showReactionField && props.messageToReact === props.message.Id ?
                    <ReactionField
                        updateReaction={props.updateReaction}
                        createReaction={props.createReaction}
                    />
                    : null
                }
            </div>
        </div>
    );
}

Message.propTypes = {
    message: PropTypes.object,
    showReactionField: PropTypes.bool,
    changeReactionFieldState: PropTypes.func,
    messageToReact: PropTypes.number,
    upvoteMessage: PropTypes.func,
    downvoteMessage: PropTypes.func,
    updateReaction: PropTypes.func,
    createReaction: PropTypes.func
}

export default Message;
import React from 'react';

const ReactionListItem = (props) => {
    return (
        <li className="mdl-list__item mdl-list__item--three-line">
            <span className="mdl-list__item-primary-content">
              <i className="material-icons mdl-list__item-icon">comment</i>
                <span>Reactie {props.reactionId}
                    <small> op message {props.messageId}</small></span>
              <span className="mdl-list__item-text-body">
                  {props.reactionContent}
              </span>
            </span>
            <span className="mdl-list__item-secondary-content">
                <span className="  mdl-list__item-secondary-action" >
                    <button onClick={props.onDelete}
                        className="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                        <i className="material-icons">delete</i> Delete
                    </button>
                </span>
            </span>
        </li>
    );
};


export default ReactionListItem;
import React, {Component} from 'react';
import Message from "./Message";
import axios from 'axios';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';
import PropTypes from "prop-types";
import {loadMessages} from "../actions/messageAction";

class MessagesList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            messages: [],
            showReactionField: false,
            messageToReact: 0,
            reaction: ''
        }

        axios.get('http://localhost:8000/messages').then(r => {
            this.props.messages(r.data)
        });
    }

    upvoteMessage(messageId){
        axios.post('http://localhost:8000/message/' + messageId + '/upvote').then(r => {
            this.props.messages(r.data)
        });
    }

    downvoteMessage(messageId){
        axios.post('http://localhost:8000/message/' + messageId + '/downvote').then(r => {
            this.props.messages(r.data)
        });
    }
    
    changeReactionFieldState(messageId) {
        let newState;

        if (this.state.showReactionField) {
            newState = false;
        } else {
            newState = true;
        }

        this.setState({showReactionField: newState, messageToReact: messageId})
    }

    updateReaction(reaction) {
        this.setState({reaction: reaction});
    }

    createReaction() {

        fetch('http://localhost:8000/reaction', {
            method: 'post',
            body: JSON.stringify({
                'messageId': this.state.messageToReact,
                'reaction': this.state.reaction.toString()
            })
        }).then(this.changeReactionFieldState(0));
    }

    render() {
        return (
            <div>
                <div className="mdl-grid">
                    {this.props.messagesList.map((message) => (
                        <Message
                            key={message.Id}
                            message={message}
                            showReactionField={this.state.showReactionField}
                            changeReactionFieldState={() => this.changeReactionFieldState(message.Id)}
                            messageToReact={this.state.messageToReact}
                            upvoteMessage={() => this.upvoteMessage(message.Id)}
                            downvoteMessage={() => this.downvoteMessage(message.Id)}
                            updateReaction={(e) => this.updateReaction(e.target.value)}
                            createReaction={() => this.createReaction()}
                        />
                    ))}
                </div>
            </div>
        )
    }
}

MessagesList.protoTypes = {
    messagesList: PropTypes.object
}

function mapStateToProps(state) {
    return {
        messagesList: state.messages[0]? state.messages[0].messages : []
    };
}

function mapDispatchToProps(dispatch) {
    return {
        messages: bindActionCreators(loadMessages, dispatch)
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(MessagesList);

import React, {Component} from 'react';
import axios from "axios";
import MessageListItem from "./MessageListItem";
import ReactionListItem from "./ReactionListItem";

class Admin extends Component {
    constructor(props){
        super(props);

        this.state = {
            messages: [],
            reactions: [],
        };

        axios.get('http://localhost:8000/messages').then(r => {
            this.setState(() => ({
                messages: r.data
            }))
        });

        axios.get('http://localhost:8000/reactions').then(r => {
            this.setState(() => ({
                reactions: r.data
            }))
        });
    }


    render() {
        return (
            <div>
                <div className="mdl-grid">
                    <div className="mdl-cell mdl-cell--6-col">
                        <h3>Messages</h3>
                        <ul className="mdl-list message-list">
                            {this.state.messages.map((message) => (
                                <MessageListItem
                                    key={message.Id}
                                    messageId={message.Id}
                                    messageContent={message.Content}
                                    messageCategory={message.Category}
                                    onDelete={() => this.deleteMessage(message.Id)}
                                />
                            ))}
                        </ul>
                    </div>

                    <div className="mdl-cell mdl-cell--6-col">
                        <h3>Reactions</h3>
                        <ul className="mdl-list message-list">
                            {this.state.reactions.map((reaction) => (
                                <ReactionListItem
                                    key={reaction.Id}
                                    reactionId={reaction.Id}
                                    reactionContent={reaction.Content}
                                    messageId={reaction.messageId}
                                    onDelete={() => this.deleteReaction(reaction.Id)}
                                />
                            ))}
                        </ul>
                    </div>
                </div>
            </div>

        );
    }


    deleteMessage(messageId){
        axios.delete('http://localhost:8000/message?messageId=' + messageId ).then(r => {
            this.setState(() => ({
                messages: r.data
            }))
        });
    }


    deleteReaction(reactionId){
        axios.delete('http://localhost:8000/reaction?reactionId=' + reactionId ).then(r => {
            this.setState(() => ({
                reactions: r.data
            }))
        });
    }



}



export default Admin;
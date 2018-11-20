import {Component, Fragment} from "react";
import React from "react";
import Message from "./Message";
import axios from "axios";
import {connect} from "react-redux";
import PropTypes from "prop-types";
import {bindActionCreators} from "redux";
import {loadMessages} from "../actions/messageAction";

class SearchMessageList extends Component {
    constructor(props) {
        super(props);

        this.state = {
            showReactionField: false,
            messageToReact: 0,
            searchWord: '',
            reaction: ''
        }

        this.props.messages([])
    }

    upvoteMessage(messageId) {
        axios.post('http://localhost:8000/message/' + messageId + '/upvote');
        this.search();
    }

    downvoteMessage(messageId) {
        axios.post('http://localhost:8000/message/' + messageId + '/downvote');
        this.search();
    }

    search = () => {
        let searchWord = this.state.searchWord;
        const id = parseInt(searchWord);
        let categoryKeyWord = 'category:';
        let result;

        if (searchWord.includes(categoryKeyWord) ) {

            let category = searchWord.substring(searchWord.indexOf(categoryKeyWord));
            category = category.substring(categoryKeyWord.length);

            if(category.includes(' ')) {
                category = category.substring(0, category.indexOf(' '))
            }

            searchWord = searchWord.replace(categoryKeyWord + category, '');

            searchWord = searchWord.trim();

            if (searchWord.length > 0) {
                result = axios.get('http://localhost:8000/message/content/'+ searchWord +'/category/' + category);
            } else {
                result = axios.get('http://localhost:8000/message/category/' + category);
            }
        } else if(isNaN(id)) {
            result = axios.get('http://localhost:8000/message/content/' + searchWord);
        } else {
            result = axios.get('http://localhost:8000/message/' + searchWord);
        }

        result.then(res => {
            if (res.data.length === undefined) {
                this.props.messages(Array(res.data));
            } else {
                this.props.messages(res.data);
            }
        })
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
            <Fragment>
                <div className="mdl-cell--4-offset">
                    <label className="mdl-button mdl-js-button mdl-button--icon" htmlFor="sample6" onClick={this.search}>
                        <i className="material-icons">search</i>
                    </label>
                    <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input className="mdl-textfield__input" type="text" id="sample3"
                               onChange={(e) => this.setState({ searchWord: e.target.value })}/>
                        <label className="mdl-textfield__label" htmlFor="sample3">Zoek een bericht.</label>
                    </div>
                </div>
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
            </Fragment>
        )
    }
}
SearchMessageList.propTypes = {
    messagesList: PropTypes.array
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

export default connect(mapStateToProps, mapDispatchToProps)(SearchMessageList);



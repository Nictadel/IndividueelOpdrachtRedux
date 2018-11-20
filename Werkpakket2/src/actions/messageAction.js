import * as types from "./actionTypes";

function loadMessagesSucces(messages) {
    return {
        type: types.LOAD_MESSAGES_SUCCES,
        body: messages
    };
}

export function loadMessages(messages) {
    return function (dispatch) {
        return dispatch(loadMessagesSucces(messages));
    };
}


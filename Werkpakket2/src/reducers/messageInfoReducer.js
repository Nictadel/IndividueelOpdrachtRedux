import * as types from "../actions/actionTypes";
import initialState from "../store/initialState";

export default function messageReducer( state = initialState.messages, action) {
    switch (action.type) {
        case types.LOAD_MESSAGES_SUCCES:
            return [
                Object.assign({}, state.messages, {messages: action.body})
            ];
        default:
            return state;
    }
}
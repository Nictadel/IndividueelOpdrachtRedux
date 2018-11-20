import {combineReducers} from 'redux';
import messages from './messageInfoReducer';
import userInfo from './userInfoReducer';


const rootReducer = combineReducers({
    messages,
    userInfo
});

export default rootReducer;
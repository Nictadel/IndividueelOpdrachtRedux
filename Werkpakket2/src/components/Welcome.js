import React from "react";

const Welcome = () => {

    return (
        <div className="mdl-grid mdl-cell--1-offset">
            <h2 className="mdl-cell mdl-cell--12-col">Welkom op het forum van Jorne, Bénedicte en Ferre</h2>
            <div className="mdl-cell--3-offset mdl-cell--4-col">
                <h4>
                    <br/><br/>
                    Dit forum is ontwikkeld in React en zou een userinterface moeten voorstellen.
                    Dit forum gaat een PHP Rest api consumeren <br/><br/>
                    Enjoy!
                </h4>
            </div>
        </div>
    )
}

export default Welcome;
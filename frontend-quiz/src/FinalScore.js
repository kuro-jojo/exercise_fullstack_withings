import React from 'react';

const FinalScore = ({ score, maxScore, retryQuiz }) => {
    return (
        <div className="final-score-container">
            <div className='score-meta'>Your score / Maximum score</div>
            <div className='score'>{score} / {maxScore}</div>
            <div className='retry-btn' onClick={retryQuiz}>Retry</div>
        </div>
    );
}

export default FinalScore;
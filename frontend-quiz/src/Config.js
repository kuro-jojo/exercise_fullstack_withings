import React, { useState } from 'react'

const Config = ({ initGame }) => {
    const [numQuestions, setNumQuestions] = useState(5)
    // we can fecth the categories from the api too
    const [category, setCategory] = useState('')
    const [difficulty, setDifficulty] = useState('easy,medium,hard')
    // const [type, setType] = useState('text_choice')

    const handleStartGame = () => {
        initGame(Math.min(Math.max(numQuestions, 1), 20), category, difficulty)
    }

    return (
        <div className="config-container">
            <div className="config-item">
                <label htmlFor="numQuestions">Number of Questions:</label>
                <input
                    type="number"
                    id="numQuestions"
                    value={numQuestions}
                    onChange={(e) => setNumQuestions(e.target.value)}
                    min="1"
                    max="10"
                />
            </div>
            <div className="config-item">
                <label htmlFor="category">Category:</label>
                <select
                    id="category"
                    value={category}
                    onChange={(e) => setCategory(e.target.value)}
                >
                    <option value="">Any Category</option>
                    <option value="general_knowledge">General Knowledge</option>
                    <option value="film_and_tv">Film & TV</option>
                    <option value="science">Science</option>
                    <option value="history">History</option>
                    <option value="geography">Geography</option>
                    <option value="sports">Sports</option>
                    <option value="the_solar_system">The solar system</option>
                </select>
            </div>
            {/* <div className="config-item">
                    <label htmlFor="type">Question type:</label>
                    <select
                        id="type"
                        value={type}
                        onChange={(e) => setType(e.target.value)}
                    >
                        <option value="text_choice">Text</option>
                        <option value="image_choice">Image</option>
                    </select>
                </div> */}
            <div className="config-item">
                <label htmlFor="difficulty">Difficulty:</label>
                <select
                    id="difficulty"
                    value={difficulty}
                    onChange={(e) => setDifficulty(e.target.value)}
                >
                    <option value="">Any Difficulty</option>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div className="start-btn" onClick={handleStartGame}>Start Quiz</div>
        </div>
    )
}

export default Config
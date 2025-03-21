import React, { useState, useEffect } from 'react';

import Question from './Question';
import FinalScore from './FinalScore';
import Config from './Config';


const App = () => {
	const numberOfQuestions = 5;

	const [questions, setQuestions] = useState([]);
	const [numberOfQuestionsAnswered, setNumberOfQuestionsAnswered] = useState(1);
	const [currentQuestion, setCurrentQuestion] = useState(null)
	const [score, setScore] = useState(0);
	const [maxScore, setMaxScore] = useState(0)
	const [isOver, setIsOver] = useState(false)
	const [isGameStarted, setIsGameStarted] = useState(false)

	const initGame = (numQuestions, category, difficulty) => {
		console.log(numQuestions, category, difficulty);
		let apiUrl
		if (category) {
			apiUrl = `https://the-trivia-api.com/v2/questions?limit=${numQuestions}&difficulties=${difficulty}`

		} else {
			apiUrl = `https://the-trivia-api.com/v2/questions?limit=${numQuestions}&tag=${category}&difficulties=${difficulty}`
		}
		setIsGameStarted(true)
		fetchQuestions(apiUrl)
	};

	const fetchQuestions = async (apiUrl) => {
		try {
			const response = await fetch(apiUrl)
			const data = await response.json()

			setQuestions(data)
			setCurrentQuestion(data[0])

			const maxScore = data.reduce((total, question) => total + mapQuestionDifficultyToScore(question.difficulty), 0);
			setMaxScore(maxScore);
		} catch (error) {
			console.error('Error fetching questions:', error)
		}
	};

	const nextQuestion = () => {
		setNumberOfQuestionsAnswered(prev => prev + 1)
		const nextIndex = numberOfQuestionsAnswered
		if (nextIndex < questions.length) {
			setCurrentQuestion(questions[nextIndex])
		} else {
			// TODO Handle end of quiz
			setIsOver(true)
			console.log('Quiz completed')
		}
	}

	const updateScore = (point) => {
		setScore(prevScore => prevScore + point)
	}

	const mapQuestionDifficultyToScore = (difficulty) => {
		switch (difficulty) {
			case 'easy':
				return 1
			case 'medium':
				return 2
			case 'hard':
				return 3
			default:
				return 0
		}
	}

	const retryQuiz = () => {
		setQuestions([]);
		setNumberOfQuestionsAnswered(1);
		setCurrentQuestion(null);
		setScore(0);
		setIsOver(false);
		setIsGameStarted(false)
	};

	useEffect(() => {
		if (isGameStarted) initGame()
	}, [])

	return (
		<div className="App">
			{isGameStarted ? (
				<>
					<div className="App-header">
						<h1>Quiz -
							{isOver ? " End" : ` Question ${numberOfQuestionsAnswered}/${numberOfQuestions}`} </h1>
					</div>
					<div className="App-content">
						{!isOver && currentQuestion && (
							<Question
								question={currentQuestion}
								nextQuestion={nextQuestion}
								updateScore={updateScore} />
						)}
						{isOver && (
							<FinalScore
								score={score}
								maxScore={maxScore}
								retryQuiz={retryQuiz} />)}
					</div></>
			) :
				<>
					<div className="App-header">
						<h1>Quiz - Configuration </h1>
					</div>
					<Config initGame={initGame} />
				</>
			}
		</div>
	);
}

export default App;
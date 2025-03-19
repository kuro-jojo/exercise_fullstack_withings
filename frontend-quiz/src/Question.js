import React, { useState, useEffect } from 'react';

const Question = ({ question, nextQuestion, updateScore }) => {
	const [selected, setSelected] = useState(null)
	const [point, setPoint] = useState(1)

	const [isAnswered, setIsAnswerd] = useState(false)

	const [highlightedAnswer, sethighlightedAnswer] = useState(null)
	// avoid rendering the answers always in the same order
	const [shuffledAnswers, setShuffledAnswers] = useState([])

	const handleClick = () => {
		if (selected !== null) {
			const isCorrect = selected === question.correctAnswer
			sethighlightedAnswer(question.correctAnswer)
			setIsAnswerd(true)

			if (isCorrect) {
				updateScore(point)
			}
		}
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

	// Transform 'film_and_tv' => 'Film & Tv
	const formatCategory = (category) => {
		return category
			.split('_')
			.map(word => word.charAt(0).toUpperCase() + word.slice(1))
			.join(' ')
			.replace('And', '&')
	}

	const getAnswerClassName = (answer) => {
		if (highlightedAnswer === answer) {
			return 'question-answer correct'
		} else if (selected === answer && !isAnswered) {
			return 'question-answer selected'
		} else if (selected === answer && isAnswered && answer !== question.correctAnswer) {
			return 'question-answer incorrect'
		} else {
			return 'question-answer default'
		}
	}

	useEffect(() => {
		// reset when question change
		setSelected(null)
		sethighlightedAnswer(null)
		setIsAnswerd(false)
		setPoint(mapQuestionDifficultyToScore(question.difficulty))

		const answers = [...question.incorrectAnswers, question.correctAnswer]
		const shuffled = answers.sort(() => Math.random() - 0.5)
		setShuffledAnswers(shuffled)
	}, [question])

	return (
		<div className="question-container">
			<div className='question-category'>{formatCategory(question.category)} - {point} pts</div>
			<div className='question-title'>{question.question.text}</div>
			<div className="question-answers">
				{shuffledAnswers.map((answer, index) => (
					<div className={getAnswerClassName(answer)}
						key={index}
						onClick={() => !isAnswered && setSelected(answer)}
					>
						{answer}
					</div>
				))}
			</div>
			{!isAnswered && <div className='submit-btn' onClick={handleClick}>Validate</div>}
			{isAnswered && <div className='next-btn' onClick={nextQuestion}>Next</div>}
		</div>
	)
}


export default Question;
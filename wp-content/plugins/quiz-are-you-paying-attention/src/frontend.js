import React, {useState, useEffect} from 'react'
import ReactDOM from 'react-dom'
import "./frontend.scss" //This line will created our style css config from our scss

const divsToUpdate = document.querySelectorAll(".paying-attention-update-me");

divsToUpdate.forEach(function (div){
    const data = JSON.parse(div.querySelector("pre").innerHTML); //this line finds an HTML <pre> (this tag is in my PHP) element within the div, extracts the HTML content from that element, and parses that content as a JSON string, turning it into a JavaScript object. The final result is stored in the data constant
    ReactDOM.render(<Quiz {...data} />, div); //the line of code is rendering a React component called <Quiz> passing all the properties contained in the data object as component properties. This is done by using the {...data} spread operator to spread the object properties as individual properties across the component
    div.classList.remove("paying-attention-update-me");
});

function Quiz(props){
    //in react we never fetch/change the dom directly, so this line of code is creating a state variable called isCorrect with an initial value of undefined. You are also creating a function called setIsCorrect that can be used to update the value of isCorrect later.
    const [isCorrect, setIsCorrect] = useState(undefined); //useState() -> react function that returns the state of the component or application
    const [isCorrectDelayed, setIsCorrectDelayed] = useState(undefined); 

    useEffect(() => {
        //If isCorrect is false, I return it to 'undefined' after 2 seconds, so I can make mistakes several times and my message will always appear
        if(isCorrect === false){
            setTimeout(() => {
                setIsCorrect(undefined);
            }, 2600);
        }

        if(isCorrect == true){
            setTimeout(() => {
                setIsCorrectDelayed(true)
            }, 1000);
        }
    }, [isCorrect]);  //the 1 param is the function that will be called, the 2 is when the fn is executed, that is, which properties or parts of the state we are observing change to call the fn again with each change

    function handleAnswer(index){
        if(index == props.correctAnswer){
            setIsCorrect(true); 
        }else{
            setIsCorrect(false);
        }
    }

    return ( //our JSX
        <div className="paying-attention-frontend">
            <p> {props.question}</p>
            <ul>
                {props.answers.map(function(answer, index){
                    return (
                        <li className={(isCorrectDelayed === true && index == props.correctAnswer ? "no-click" : "") + (isCorrectDelayed === true && index != props.correctAnswer ? "fade-incorrect" : "")} onClick={isCorrect === true ? undefined : () => handleAnswer(index)}>
                            {isCorrectDelayed === true && index == props.correctAnswer && (
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" className="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                </svg>
                            )}
                            {isCorrectDelayed === true && index != props.correctAnswer && (
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" className="bi bi-x" viewBox="0 0 16 16">
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            )}
                            {answer}
                        </li>
                    )
                })}
            </ul>
            <div className={"correct-message" + (isCorrect == true ? " correct-message--visible" : '')}>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"  className="bi bi-emoji-smile" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                    <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                </svg>
                <p> That is correct </p>
            </div>
            <div className={"incorrect-message" + (isCorrect === false ? " correct-message--visible" : '')}>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" className="bi bi-emoji-tear" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14Zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16Z"/>
                    <path d="M6.831 11.43A3.1 3.1 0 0 1 8 11.196c.916 0 1.607.408 2.25.826.212.138.424-.069.282-.277-.564-.83-1.558-2.049-2.532-2.049-.53 0-1.066.361-1.536.824.083.179.162.36.232.535.045.115.092.241.135.373ZM6 11.333C6 12.253 5.328 13 4.5 13S3 12.254 3 11.333c0-.706.882-2.29 1.294-2.99a.238.238 0 0 1 .412 0c.412.7 1.294 2.284 1.294 2.99ZM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5Zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5Zm-1.5-3A.5.5 0 0 1 10 3c1.162 0 2.35.584 2.947 1.776a.5.5 0 1 1-.894.448C11.649 4.416 10.838 4 10 4a.5.5 0 0 1-.5-.5ZM7 3.5a.5.5 0 0 0-.5-.5c-1.162 0-2.35.584-2.947 1.776a.5.5 0 1 0 .894.448C4.851 4.416 5.662 4 6.5 4a.5.5 0 0 0 .5-.5Z"/>
                </svg>
                <p> Sorry is wrong! Try again </p>
            </div>
        </div>
    )
}
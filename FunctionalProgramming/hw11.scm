//(require "src\Typelang\lib\hw11.scm")// Name: Jason Wakeman
// Procedures for Homework 11 
// Put this file in directory examples of your Funclang interpreter
// You can then load this file by typing the following on the command-line



(define zip
	("not defined"
	)
)

//Question 1.

//1. (let ((x 5)) (let ((y x)) y))		:         whole program is of type num
//2. (null? (list  1 2 8))				:         whole program is of type bool
//3. (let ((a 3)) (let ((p  (lambda (x) (- x a)))) (let ((a  5)) (- a (p 2)))))		:         whole program is of type num
//4. (let ((class (ref 342))) (set! class 541))		:         whole program is of type num
//5. (let ((f (lambda (x) (lambda (y) y)))) (f 2))		:         whole program is of type function which takes in a single expression which evaluates to type G and returns a value of the same type G. 
//6. (let ((class (ref 342))) (let ((d (free class))) (deref class)))		:         whole program is of type error (can not infer the type a reference refers to if that location has been freed )
//7. (letrec ((fact (lambda (n) (if (= n 0) 1 (* n (fact (- n 1)))))))(fact 5)) 		:         whole program is of type num
//8. (letrec ((isEven (lambda (n) (if (= 0 n) #t (isOdd (- n 1))))) (isOdd (lambda (n) (if (= 0 n) #f (isEven (- n 1)))))) (isOdd 11))
//9. (cdr (list  1 2 8))		:         whole program is of type bool
//10. (let ((r (ref (ref 5)))) (deref(deref r)))		:         whole program is of type num




//Qeustion 2:


//1. A list must be entirely composed of elements of a single type. otherwise the type of list cannot be inferred.

(let ((x #t)) (list 20 41 59 19 20 589 398 4 098 240 484 x))

//2. //An if statement must return the same type for all conditions in order to be able to infer the type of the program.

(letrec ((fact (lambda (n) (if (= n 0) #t (* n (fact (- n 1)))))))(fact 5)) 


//3.  Will fail to be able to infer the desired behavior because listY is not composed entirely of elements of the same type. 

(let ((listX (list 1 3 5) ) (listY (list "stringValue" 11 13 17 19) )) (list (car listX) (car listY) (+ (car listX) (car listY) ) (- (car listX) (car listY) ) (/ (+ (car listX) (car listY) ) 2)))


//4. returns the boolean value passed in if the variable passed into this lambda is #t,  otherwise it tries to perform arithmetic on a boolean value. Makes inferring the type of value the function maps to impossible.

(lambda (booleanVar) (if (= booleanVar #t) #t (- booleanVar 1)))

//5. the call function is calling this lambda with an argument of type List, where the lambda expression and therefore the program infers only values of type num. thus the program cannot be inferred to anything but an error. 

 ((lambda (x y z ) (if (< (+ x y z) 0) (< 0 1) (< 1 0))) (- 0 20) 5 (list 6 9))


//6. the program can not infer the type of 5 times the list (10).  

((lambda (x ) (let ((class (ref x))) (let ((d (deref class))) (d (list 5 10))))) (lambda (y) (* (car y) (cdr y))))


//7. Depending on if the interpretter has reference arithmetic functionality, this will either fail to be able to infer the value at location 2 + wherever the 5 is being stored when the deref of that location is called, or if the interpretter does not allow for reference arithmetic it will fail to be able to infer what location we are trying to deref because it does not allow the addition of a reference Value Type and a num Value Type.

(let ((r (ref (ref 5)))) (deref(+ (deref r) 2))))	


//8.Cannot infer what mixedList is a list of. 

(define mixedList (deref (ref (list 5 #t "word" (list 5 #t (list ))))))

//9. the variable reference refers to a reference which refers to a num Type, but then an attempt to set it to refer to a num Type is made. if the interpretter only allows a location to hold a value of a certain type this program will fail. 

(let ((reference (ref (ref #t)))) (set! reference (ref 42)))


//10. Attempts to concatenate two lists of strings using "+" instead of cons or some concatenate function, therefore the interpreter cannot infer the type of the program.

(let ((listX (list "342" "is" "great") ) ) ((lambda (listXVar stringVar) (+ listX (list (cons  stringVar(cdr listX))  ))) listX "331"))




"Loaded procedures for Homework 11"




Ms. Anderson;

Thank you for the meeting yesterday. It was a pleasure hearing how your team of professional developers has worked to make the MS in Agronomy distance learning program so successful. I hope for the privilege to join such a team and for the opportunity to contribute to such a valuable project. To me, the most appealing aspect of the student develop position is the possibility of writing code which could be used by Iowa State University to implement other distance learning programs even after I graduate. 
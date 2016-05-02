//(require "src\forklang\lib\hw8.scm")// Name: Jason Wakeman
// Procedures for Homework 8 
// Put this file in directory examples of your Funclang interpreter
// You can then load this file by typing the following on the command-line



(define zip
	("not defined"
	)
)

//3.a   NOLOCKS



(let ((x (ref 10)))(fork (((lambda (z) ( lambda (y) (set! x (+ (deref z)(deref y))))) x) x)(((lambda (z) ( lambda (y) (set! x (- (deref z)(deref y))))) x) x)))


(let ((x (ref 1))) (let((forkToCall (fork (let ((dummy3 (set! x (* (deref x) (deref x) (deref x) (deref x) (deref x) (deref x) (deref x)))))(deref x))(let ((dummy6 (set! x (+ 1 (deref x)))))(deref x)))))forkToCall))


//3.b     WITHLOCKS


(let ((x (ref 10)))	(fork (let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (+ (deref z)(deref y))))) x) x)) (dummy2 (unlock x))) (deref x)))(let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (- (deref z)(deref y))))) x) x)) (dummy2 (unlock x))) (deref x)))))


(let ((x (ref 1))) (let((forkToCall (fork (let ((dummy2 (lock x))(dummy3 (set! x (* (deref x) (deref x) (deref x) (deref x) (deref x) (deref x) (deref x))))(dummy4 (unlock x)))(deref x))(let ((dummy5 (lock x))(dummy6 (set! x (+ 1 (deref x))))(dummy7 (unlock x)))(deref x)))))forkToCall))


//3.c    WITHSYNCH

(let ((x (ref 10)))(fork (((lambda (z) ( lambda (y) (synchronized x (set! x (+ (deref z)(deref y)))))) x) x)(((lambda (z) ( lambda (y) (synchronized x (set! x (- (deref z)(deref y)))))) x) x)))

(let ((x (ref 1))) (let((forkToCall (fork (synchronized x (let ((dummy3 (set! x (* (deref x) (deref x) (deref x) (deref x) (deref x) (deref x) (deref x)))))(deref x)))(synchronized x (let ((dummy6 (set! x (+ 1 (deref x)))))(deref x))))))forkToCall))



//5.a   WITHDEADLOCKS

(let ((x (ref 10)))	(fork (let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (+ (deref z)(deref y))))) x) x)) ) (deref x)))(let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (- (deref z)(deref y))))) x) x)) ) (deref x)))))


(let ((x (ref 1))) (let((forkToCall (fork (let ((dummy2 (lock x))(dummy3 (set! x (* (deref x) (deref x) (deref x) (deref x) (deref x) (deref x) (deref x)))))(deref x))(let ((dummy5 (lock x))(dummy6 (set! x (+ 1 (deref x)))))(deref x)))))forkToCall))


//5.b   Removal of DEADLOCKS

(let ((x (ref 10)))	(fork (let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (+ (deref z)(deref y))))) x) x)) (dummy2 (unlock x))) (deref x)))(let ((dummy (lock x) ))(let  (( execute (((lambda (z) ( lambda (y) (set! x (- (deref z)(deref y))))) x) x)) (dummy2 (unlock x))) (deref x)))))


(let ((x (ref 1))) (let((forkToCall (fork (let ((dummy2 (lock x))(dummy3 (set! x (* (deref x) (deref x) (deref x) (deref x) (deref x) (deref x) (deref x))))(dummy4 (unlock x)))(deref x))(let ((dummy5 (lock x))(dummy6 (set! x (+ 1 (deref x))))(dummy7 (unlock x)))(deref x)))))forkToCall))


"Loaded procedures for Homework 8"
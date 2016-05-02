(require "build/funclang/examples/hw4.scm")// Name: Jason Wakeman
// Procedures for Homework 4 
// Put this file in directory examples of your Funclang interpreter
// You can then load this file by typing the following on the command-line
// (require "build/funclang/examples/hw4.scm")


(define zip
	(lambda (fst snd)
		"not defined"
	)
)

(define raddn (lambda (n r) (+ n r)))
(raddn 1 (ref 342))
(define raddlist (lambda (lis r) (if (null? lis) lis (cons lis r))))
(raddlist listA 10)
(define raddlistw (lambda (lis r) (if (null? lis) lis (+ (car lis) r))))
(define listB (2 3 5))

(define listB (list 3 2 4))
(raddlistw listA 3)
(raddlistw listB 2)

(raddlistw listB (ref 23))

(raddlistw listA (ref 20))


(raddlistw listB (ref 20))


"Loaded procedures for Homework 7"
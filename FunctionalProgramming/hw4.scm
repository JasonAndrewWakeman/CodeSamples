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




(define pi 3.14159265359)

(define fourByThree 1.33333)

(define volume (lambda(r) (* pi r fourByThree r r)))



(define board (lambda(b) (lambda(r) (lambda(d) (list (list r b b r) (list b r r b) (list d r r d) (list r d d r))))))


(define boardz (lambda(b d) (((board b ) 0 ) d)))


(define board10 (lambda(d) (boardz 1 d)))







 (define listmaker (lambda (lst)  (cons 1 lst)))
 (define ls (list 4 2 0) )

 (define listodd (lambda (lst) (cons 1 lst)))
 (define listeven (lambda (lst) (cons 0 lst)))
 (define listhelper (lambda (listOfLists count odd) (if (< 0 count) (if (= odd 1) (listhelper (listodd listOfLists) (- count 1) 0) (listhelper (listeven listOfLists) (- count 1) 1))  listOfLists )))

 (define board (lambda (size) (listhelper (list) size 0)))



(define board12 (lambda() (board10 2)))

 (define sumhelper (lambda (list1 x) (if (null? list1) x (sumhelper (cdr list1) (+ x (car list1))))))
 (define sum (lambda (listToSum) (if (null? listToSum) listToSum (sumhelper listToSum 0))))


(define revhelper (lambda (list1 list2) (if (null? list1) list2 (revhelper (cdr list1) (cons (car list1) list2)))))
(define rev (lambda (listToRev) (if (null? listToRev) listToRev (revhelper listToRev (list )))))


(define freqhelper (lambda (list1 elem x) (if (null? list1) x (if (= elem (car list1)) (freqhelper (cdr list1) elem (+ x 1)) (freqhelper (cdr list1) elem x)))))
(define frequency (lambda (listToSearch element) (if (null? listToSearch) listToSearch (freqhelper listToSearch element 0))))


"Loaded procedures for Homework 4"


$ (define pi 3.14159265359)
false
$ (define fourByThree 1.33333)
false
$ (define volume (lambda(r) (* pi r fourByThree r r)))
false
$ (define sum (lambda (listToSum) (if (null? listToSum) listToSum (cons listToSum 0))))
false
$ (lista (list))
reflang.Env$LookupException: No binding found for name: lista
$ (define listA (list))
false
$ (sum (listA))
(Operator not a function in call (listA ) 0)
(sum listA)
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

(let ((c (ref (list (ref (list (ref 342) (ref 541))) (ref( list (ref 20) (ref 9) )) )) )(d 10)) (reachable c))
 (let ((c (ref (list (ref (list (ref 342) (ref 541))) (ref( list (ref 20) (ref 9) )) )) )(d (* 2 10))) (reachable c))
(letrec ((isEven : (num -> boolean) (lambda (n : (num -> boolean)) (if (= 0 n) #t (isOdd (- n 1))))) (isOdd : (num -> boolean) (lambda (n : (num -> boolean)) (if (= 0 n) #f (isEven (- n 1)))))) (isOdd 11))

//Qeustion 1.


//(1.1) Write 2 well-typed TypeLang programs that use lambda expressions and 1 well-typed program that uses call expressions. 

//(a)
////takes a value of type Boolean in and if it is true returns 1, if it is false returns 0. will not work because the 
//interpreter expects the (= x y) expression to only compare num's apparently.

(lambda (booleanVar : (bool -> num)) (if (= booleanVar #t) (+ 0 1) (+ 0 0)))

//alternatively, to see if the syntax is correct:
(lambda (booleanVar : (bool -> num)) (if (= 1 1) (+ 0 1) (+ 0 0)))


//(b)
//if the flag is set to 1, then return the first list (listX), otherwise return the second list (listY). 
(let ((listX : List<num> (list : num 1 3 5) ) (listY : List<num> (list : num 7 11 13) )) (lambda (listX flag listY : (List<num> num List<num> -> List<num>)) (if (= flag 1) (cons (car listX) (cdr listX)) (cons (car listY) (cdr listY)))))




//(c)
//determines if the sum of xyz is a negative number, returns true if yes, false otherwise
((lambda (x y z : (num num num -> bool)) (if (< (+ x y z) 0) (< 0 1) (< 1 0))) (- 0 20) 5 6)


//(1.2) Write 1 ill-typed TypeLang program that uses lambda expressions and 1 ill-typed program that uses call expressions. 

//(a)
//attempts to find the square of a boolean value
(lambda (x : (bool -> num)) (* x x) )

//(b)
//attempts to multiply the first element of listX by the first element of listY-- should expect lists as input instead of nums

(let ((listX : List<num> (list : num 1 3 5) ) (listY : List<num> (list : num 7 11 13) )) ((lambda (listX listY : (num num -> num)) (* (car listX) (car listY) ) ) listX listY))


//Question 2.

//(2.1) Write 3 well-typed programs that use let expressions.

//(a) 
(let ((booleanVar : bool  #t)) booleanVar)

//(b)
//replaces the first boolean value in listX with whatever boolean value is passed in to the lambda function.
 (let ((listX : List<bool> (list : bool #t #f #t) ) ) ((lambda (listXVar booleanVar: (List<bool> bool -> List<bool> )) (cons  booleanVar(cdr listX)  )) listX #f))

//(c)
((let ((x : num  42) (y : num 0)) (list : num x y))

//(2.2) Write 2 ill-typed programs that use let expressions.

//(a)
(let ((x : bool #t)) (list : num 20 41 59 19 20 589 398 4 098 240 484 x))


//(b)

(let ((listX : List<num> (list : num 1 3 5) ) (listY : List<num> (list : num 7 11 13 17 19) )) (list : List<unit> listX listY))


//Question 3

//(3.1)

//(a)

//identical to the example given on the homework, instructions just said it must differ from any of our programs. 
 (letrec ((isEven : (num -> bool) (lambda (n : (num -> bool)) (if (= 0 n) #t (isOdd (- n 1))))) (isOdd : (num -> bool) (lambda (n : (num -> bool)) (if (= 0 n) #f (isEven (- n 1)))))) (isOdd 11))


//(b)
 (letrec ((addList : (num List<num> -> num) (lambda (n lst : (num  List<num> -> num)) (if (=null lst) n (addlist (+ n (car lst)) (cdr lst)) ) ) 0 (list : num 4 5 6 7 9))))

//c

(letrec ((addList : (num List<num> -> num) (lambda (n lst : (num  List<num> -> num)) (if (= n 0) "Pizza" (addlist (- n 1) ))))))

//3.2

//a
(letrec ((isEven : (unit-> bool) (lambda (n : (num -> bool)) (if (= 0 n) #t (isOdd (- n 1))))) (isOdd : (num -> bool) (lambda (n : (num -> bool)) (if (= 0 n) #f (isEven (- n 1)))))) (isOdd 11))


//b

(letrec ((isEven : (num -> bool) (lambda (n : (num -> bool)) (if (= 0 n) #t (isOdd (- n 1))))) (isOdd : (num -> bool) (lambda (n : (num -> bool)) (if (= 0 n) #f (isEven (- #t 1)))))) (isOdd 11))


//Question 4.

//(4.1) Write 3 well-typed programs  that use list expressions.

//(a)
(cons (list : num 3 4 2) (list : num  342 42))

//(b) 
//constructs a list consisting of two sets, one containing 3, 4, and 2 and the other containing 7 of the ways to assemble the elements 3, 4, and 2
(cons (list : num 3 4 2) (list : List<num>   (list : num 342 34 32 42 3 4 2 )))

//(c)
//if the flag is set to one it returns the first number of the first list of numbers in listX, otherwise it returns the first number of the second list of numbers of listX. 

(let ((listX : List<List<num>> (list : List<num> (list : num 1 2 3 4 5 6 7 8 9 10) (list : num 11 12 13 14 15 16 17 18 19 20) (list : num 21 22 23 24 25 26 27 28 29 30)) )) ((lambda (listX flag : (List<List<num>> num -> num)) (if (= flag 1) (car (car listX)) (car (car (cdr listX))))) listX 0 ))



//(4.2) Write 2 ill-typed programs that use list expressions. 

//(a)
//attempts to include an empty list in a list of <num>
(cons (list : num 3 4 2) (list : List<num>   (list : num 342 34 32 42 3 4 2  (cdr (list : num )))))

//(b)

(let ((listX : List<bool> (list : bool #t #f #t) )(listY : List<num> (list : num 3 4 2) ) ) ((lambda (listXVar listYVar: (List<bool>  List<num> -> num )) (if ( = 1 1) (car listY) (car listX))) listX listY))

//probably should throw a type checking error but doesn't, instead it returns a list of mixed types-- one element is a list of nums and the other is a single boolean value
(cons (list : num 2 3 5) #t)

//Question 5.


//(5.1) Write 3 well-typed programs that use reference expressions.


//(a)

(ref : String "Pizza")

//(b)

(let ((listX : List<String> (list : String "pizza" "tacos" "smoothie") )) (lambda (listXVar : (List<String> -> Ref String)) (ref : String (car listX)))))

//(c)
(let ((reference : Ref Ref bool (ref : Ref bool (ref : bool #t)))) (set! reference (ref : bool #t)))



//5.2) Write 2 ill-typed programs that use reference expressions

//(a)
 (let ((listX : List<String> (list : String "pizza" "tacos" "smoothie") )) ((lambda (listXVar : (List<String> -> String)) (let ((firstStringReference : Ref String (car listX))) (deref firstStringReference)))listX )  )))


//(b)
//must use modified interpreter which type checks deref expression 
(let ((r:Ref Ref num (ref:Ref num (ref:num 5)))) (deref: Ref num (deref: Ref Ref bool r)))

 
 

 after evaluating the first two exp add their values as val1 and val2 to the environment and then evaluate the the third exp in that env
all: crop overlay colorshift bw


subfolder: cd subfolder/
		make
		cd ..

crop.o : crop.c p1header.h
	gcc -ansi -pedantic -c crop.c
overlay.o : overlay.c p1header.h
	gcc -ansi -pedantic -c overlay.c
bw.o : bw.c p1header.h
	gcc -ansi -pedantic -c bw.c
colorshift.o : colorshift.c p1header.h
	gcc -ansi -pedantic -c colorshift.c

crop: crop.o
	gcc -ansi -pedantic -o crop crop.o
overlay: overlay.o
	gcc -ansi -pedantic -o overlay overlay.o
colorshift: colorshift.o
	gcc -ansi -pedantic -o colorshift colorshift.o
bw: bw.o
	gcc -ansi -pedantic -o bw bw.o

clean:
	rm -rf *o crop overlay colorshift bw

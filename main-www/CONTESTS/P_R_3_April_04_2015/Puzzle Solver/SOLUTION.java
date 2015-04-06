import java.util.Arrays;
import java.util.Scanner;


public class PuzzleSolver {
	public static void main(String[] args) {
		Scanner br = new Scanner(System.in);
		int numWords = br.nextInt();
		
		Word[] words = new Word[numWords];
		for(int i = 0; i < words.length; i++){
			words[i] = new Word(br.next());
		}
		
		Arrays.sort(words);
		
		int longWords = br.nextInt();
		br.nextLine();
		
		for(int w = 0; w < longWords; w++){			
			Word longWord = new Word(br.nextLine());
			
			System.out.println(longWord.original);
			for(Word small : words){
				if(longWord.contains(small)){
					System.out.printf("   %s%n", small.original);
				}
			}
			
			System.out.println();
		}
		
		System.out.flush();
	}
	
	// By making a separate class, we can couple the original word with a sorted version of the word
	// This makes the comparisons a lot easier (and faster)
	private static class Word implements Comparable<Word>
	{
		public final String original, sorted;
		public Word(String str){
			original = str;
			
			// Long words can have upper case/spaces: transform them.
			String transformed = str.toLowerCase().replaceAll(" ", "");
			
			// Clone the letters into an array (so we can sort)
			char[] letters = new char[transformed.length()];
			for(int i = 0; i < letters.length; i++)
				letters[i] = transformed.charAt(i);
			
			Arrays.sort(letters);
			
			sorted = new String(letters);
		}
		
		// This is for making sure the words are printed in alphabetical order
		public int compareTo(Word o){
			return original.compareTo(o.original);
		}
		
		// This is where the magic happens. We know that both words have all lowercase letters, in sorted order.
		// If we have two pointers, one for the long word and one for the small
		// and move the long word's index after each iteration,
		// but only move the small word's index after each iteration if the characters matched,
		// Then we know the small word can be made if we go past the end of the small word
		// Ex. trying to see if cat can be made from carpet:
		// Cat sorted is act, cCarpet sorted is aceprt
		// aceprt aceprt aceprt aceprt aceprt aceprt
		// ^       ^       ^       ^       ^       ^
		// act    act    act    act    act    act     <-- We had to wait to match the t, but we found it anyways!
		// ^       ^       ^      ^      ^      ^
		//
		// If we ever come across a letter in the small word that comes before the letter in the big word, we know it isn't possible to make the small word
		// because we know everything later in the long word will be even bigger, so we can stop early.
		public boolean contains(Word other){
			int otherIndex, thisIndex;
			for(thisIndex = 0, otherIndex = 0; thisIndex < sorted.length() && otherIndex < other.sorted.length(); thisIndex++){
				if(sorted.charAt(thisIndex) == other.sorted.charAt(otherIndex)){
					otherIndex++;
				} else if(sorted.charAt(thisIndex) > other.sorted.charAt(otherIndex)) { // Only bigger ones follow, can never match char at otherIndex
					return false;
				}
			}
			
			return otherIndex >= other.sorted.length();
		}
	}
}

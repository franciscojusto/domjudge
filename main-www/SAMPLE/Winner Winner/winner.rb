

games = gets.chomp.to_i;
for game in 1..games
	contestants = gets.chomp.to_i;
	max = 0;
	for c in 1..contestants do
		score = gets.chomp.to_i;
		if score > max
			max = score;
		end
	end
	puts "The winning score is... #{max} points!"
end
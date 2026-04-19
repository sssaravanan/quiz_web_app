<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizQuestions = [
            'General Knowledge Basics' => [
                [
                    'question_text' => 'Which planet is known as the Red Planet?',
                    'explanation' => 'Mars appears reddish because of iron oxide on its surface.',
                    'options' => ['Earth', 'Mars', 'Jupiter', 'Venus'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is the capital city of Japan?',
                    'explanation' => 'Tokyo is the capital and largest city of Japan.',
                    'options' => ['Kyoto', 'Seoul', 'Tokyo', 'Osaka'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'How many continents are there on Earth?',
                    'explanation' => 'The widely accepted count is seven continents.',
                    'options' => ['Five', 'Six', 'Seven', 'Eight'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which ocean is the largest?',
                    'explanation' => 'The Pacific Ocean is the largest and deepest ocean.',
                    'options' => ['Atlantic Ocean', 'Indian Ocean', 'Pacific Ocean', 'Arctic Ocean'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Who wrote "Romeo and Juliet"?',
                    'explanation' => 'William Shakespeare wrote the famous tragedy.',
                    'options' => ['Charles Dickens', 'William Shakespeare', 'Jane Austen', 'Mark Twain'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which gas do plants absorb from the atmosphere?',
                    'explanation' => 'Plants use carbon dioxide during photosynthesis.',
                    'options' => ['Oxygen', 'Nitrogen', 'Carbon Dioxide', 'Hydrogen'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is the hardest natural substance on Earth?',
                    'explanation' => 'Diamond is the hardest naturally occurring substance.',
                    'options' => ['Gold', 'Iron', 'Diamond', 'Silver'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which country is famous for the pyramids of Giza?',
                    'explanation' => 'The pyramids of Giza are located in Egypt.',
                    'options' => ['Mexico', 'Peru', 'Egypt', 'India'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is the primary language spoken in Brazil?',
                    'explanation' => 'Portuguese is the official language of Brazil.',
                    'options' => ['Spanish', 'Portuguese', 'French', 'English'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which instrument has black and white keys?',
                    'explanation' => 'A piano is played using black and white keys.',
                    'options' => ['Violin', 'Drum', 'Flute', 'Piano'],
                    'correct_option' => 3,
                ],
            ],
            'Everyday Science Challenge' => [
                [
                    'question_text' => 'What part of the cell contains genetic material?',
                    'explanation' => 'The nucleus stores most of the cell genetic material.',
                    'options' => ['Cell wall', 'Nucleus', 'Cytoplasm', 'Ribosome'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which force keeps planets in orbit around the Sun?',
                    'explanation' => 'Gravity holds planets in orbit around the Sun.',
                    'options' => ['Magnetism', 'Friction', 'Gravity', 'Radiation'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is the chemical symbol for water?',
                    'explanation' => 'Water is composed of two hydrogen atoms and one oxygen atom.',
                    'options' => ['O2', 'H2O', 'CO2', 'NaCl'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which organ in the human body pumps blood?',
                    'explanation' => 'The heart pumps blood throughout the body.',
                    'options' => ['Lungs', 'Liver', 'Brain', 'Heart'],
                    'correct_option' => 3,
                ],
                [
                    'question_text' => 'What type of energy is stored in food?',
                    'explanation' => 'Food stores chemical energy that the body can use.',
                    'options' => ['Thermal energy', 'Chemical energy', 'Light energy', 'Sound energy'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which vitamin is mainly produced when skin is exposed to sunlight?',
                    'explanation' => 'The body synthesizes vitamin D with sunlight exposure.',
                    'options' => ['Vitamin A', 'Vitamin B12', 'Vitamin C', 'Vitamin D'],
                    'correct_option' => 3,
                ],
                [
                    'question_text' => 'What is the boiling point of water at sea level in Celsius?',
                    'explanation' => 'At standard atmospheric pressure, water boils at 100 degrees Celsius.',
                    'options' => ['50', '90', '100', '120'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which planet has the most prominent ring system?',
                    'explanation' => 'Saturn is best known for its visible rings.',
                    'options' => ['Mars', 'Saturn', 'Mercury', 'Venus'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is the main gas found in the air we breathe?',
                    'explanation' => 'Nitrogen makes up about 78 percent of Earth atmosphere.',
                    'options' => ['Oxygen', 'Carbon Dioxide', 'Nitrogen', 'Helium'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which branch of science studies weather?',
                    'explanation' => 'Meteorology is the study of weather and atmospheric conditions.',
                    'options' => ['Geology', 'Meteorology', 'Ecology', 'Astronomy'],
                    'correct_option' => 1,
                ],
            ],
            'Mathematics Mastery' => [
                [
                    'question_text' => 'What is 12 multiplied by 8?',
                    'explanation' => '12 x 8 equals 96.',
                    'options' => ['88', '94', '96', '98'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is the value of pi rounded to two decimal places?',
                    'explanation' => 'Pi rounded to two decimal places is 3.14.',
                    'options' => ['3.12', '3.14', '3.16', '3.18'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is the square root of 144?',
                    'explanation' => '12 multiplied by itself equals 144.',
                    'options' => ['10', '11', '12', '14'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'How many degrees are in a right angle?',
                    'explanation' => 'A right angle measures exactly 90 degrees.',
                    'options' => ['45', '60', '90', '180'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is 15 percent of 200?',
                    'explanation' => '15 percent of 200 is 30.',
                    'options' => ['20', '25', '30', '35'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What is the next prime number after 11?',
                    'explanation' => '13 is the next prime number after 11.',
                    'options' => ['12', '13', '14', '15'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is the perimeter of a square with side length 6?',
                    'explanation' => 'Perimeter of a square is 4 times the side length.',
                    'options' => ['12', '18', '24', '36'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Solve: 9 + 6 / 3',
                    'explanation' => 'Division comes first, so 6 / 3 = 2 and 9 + 2 = 11.',
                    'options' => ['5', '11', '15', '27'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'How many sides does a hexagon have?',
                    'explanation' => 'A hexagon is a six-sided polygon.',
                    'options' => ['5', '6', '7', '8'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is 7 squared?',
                    'explanation' => '7 squared means 7 multiplied by 7.',
                    'options' => ['14', '42', '49', '56'],
                    'correct_option' => 2,
                ],
            ],
            'World History Essentials' => [
                [
                    'question_text' => 'Who was the first President of the United States?',
                    'explanation' => 'George Washington served as the first U.S. President.',
                    'options' => ['Thomas Jefferson', 'George Washington', 'Abraham Lincoln', 'John Adams'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'In which year did World War II end?',
                    'explanation' => 'World War II ended in 1945.',
                    'options' => ['1942', '1945', '1948', '1950'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which ancient civilization built Machu Picchu?',
                    'explanation' => 'Machu Picchu was built by the Inca civilization.',
                    'options' => ['Maya', 'Aztec', 'Inca', 'Roman'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'The Great Wall is primarily located in which country?',
                    'explanation' => 'The Great Wall is one of the most famous landmarks in China.',
                    'options' => ['India', 'China', 'Japan', 'Mongolia'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Who discovered penicillin?',
                    'explanation' => 'Alexander Fleming discovered penicillin in 1928.',
                    'options' => ['Louis Pasteur', 'Isaac Newton', 'Alexander Fleming', 'Marie Curie'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which empire was ruled by Julius Caesar?',
                    'explanation' => 'Julius Caesar was a Roman statesman and military general.',
                    'options' => ['Greek Empire', 'Roman Empire', 'Ottoman Empire', 'British Empire'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which ship famously sank in 1912 after hitting an iceberg?',
                    'explanation' => 'The Titanic sank on its maiden voyage in 1912.',
                    'options' => ['Britannic', 'Lusitania', 'Titanic', 'Mayflower'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Who was known as the Maid of Orleans?',
                    'explanation' => 'Joan of Arc is often called the Maid of Orleans.',
                    'options' => ['Cleopatra', 'Queen Victoria', 'Joan of Arc', 'Catherine the Great'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'The Renaissance began in which country?',
                    'explanation' => 'The Renaissance began in Italy before spreading across Europe.',
                    'options' => ['France', 'Italy', 'Spain', 'Germany'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which war was fought between the North and South regions of the United States?',
                    'explanation' => 'The American Civil War was fought between the Union and the Confederacy.',
                    'options' => ['World War I', 'American Civil War', 'Vietnam War', 'Korean War'],
                    'correct_option' => 1,
                ],
            ],
            'Modern Technology Trends' => [
                [
                    'question_text' => 'What does CPU stand for?',
                    'explanation' => 'CPU stands for Central Processing Unit.',
                    'options' => ['Central Process Utility', 'Computer Processing Unit', 'Central Processing Unit', 'Core Performance Unit'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which company developed the Android operating system?',
                    'explanation' => 'Android was originally developed by Android Inc. and later acquired by Google.',
                    'options' => ['Apple', 'Google', 'Microsoft', 'IBM'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What does HTTP stand for?',
                    'explanation' => 'HTTP stands for HyperText Transfer Protocol.',
                    'options' => ['HyperText Transfer Protocol', 'High Transfer Text Protocol', 'Hyperlink Transmission Tool Process', 'Host Transfer Terminal Protocol'],
                    'correct_option' => 0,
                ],
                [
                    'question_text' => 'Which storage type has no moving mechanical parts?',
                    'explanation' => 'Solid-state drives store data electronically without moving parts.',
                    'options' => ['HDD', 'SSD', 'Magnetic tape', 'Optical disc'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What is the main purpose of a firewall in networking?',
                    'explanation' => 'A firewall filters traffic to help protect systems and networks.',
                    'options' => ['Increase monitor brightness', 'Cool the processor', 'Filter network traffic', 'Boost internet speed automatically'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which language is primarily used to style web pages?',
                    'explanation' => 'CSS is used to style and format web pages.',
                    'options' => ['HTML', 'Python', 'CSS', 'SQL'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What does AI commonly stand for in technology?',
                    'explanation' => 'AI stands for Artificial Intelligence.',
                    'options' => ['Automated Internet', 'Artificial Intelligence', 'Advanced Interface', 'Applied Informatics'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which device converts digital signals for transmission over some internet connections?',
                    'explanation' => 'A modem modulates and demodulates signals for data communication.',
                    'options' => ['Router', 'Switch', 'Modem', 'Repeater'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'What does URL stand for?',
                    'explanation' => 'URL means Uniform Resource Locator.',
                    'options' => ['Universal Reference Link', 'Uniform Resource Locator', 'Unified Resource Line', 'User Routing Locator'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which technology is commonly used to make cryptocurrencies tamper-resistant?',
                    'explanation' => 'Blockchain uses distributed ledgers and cryptographic linking of blocks.',
                    'options' => ['Bluetooth', 'Blockchain', 'NFC', 'Virtual RAM'],
                    'correct_option' => 1,
                ],
            ],
            'Sports Trivia Arena' => [
                [
                    'question_text' => 'How many players are on a standard soccer team on the field at one time?',
                    'explanation' => 'A soccer team fields 11 players, including the goalkeeper.',
                    'options' => ['9', '10', '11', '12'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'In which sport is the term "slam dunk" used?',
                    'explanation' => 'A slam dunk is a scoring move in basketball.',
                    'options' => ['Volleyball', 'Basketball', 'Tennis', 'Baseball'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which country hosts the Wimbledon Championships?',
                    'explanation' => 'Wimbledon is held in London, England.',
                    'options' => ['Australia', 'France', 'United Kingdom', 'United States'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'How many points is a touchdown worth in American football before the extra point?',
                    'explanation' => 'A touchdown is worth 6 points before any conversion attempt.',
                    'options' => ['3', '6', '7', '8'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'Which sport uses a shuttlecock?',
                    'explanation' => 'Badminton is played using a shuttlecock.',
                    'options' => ['Table Tennis', 'Badminton', 'Squash', 'Cricket'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'How many holes are played in a full round of golf?',
                    'explanation' => 'A standard full round of golf consists of 18 holes.',
                    'options' => ['9', '12', '18', '24'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'In cricket, what is it called when a bowler takes three wickets in three consecutive balls?',
                    'explanation' => 'This achievement is called a hat-trick.',
                    'options' => ['Clean sweep', 'Power play', 'Hat-trick', 'Googly'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'Which sport is associated with the Tour de France?',
                    'explanation' => 'The Tour de France is one of the best-known cycling races.',
                    'options' => ['Running', 'Cycling', 'Skiing', 'Rowing'],
                    'correct_option' => 1,
                ],
                [
                    'question_text' => 'What color flag is typically waved to start a motor race?',
                    'explanation' => 'A green flag is used to signal the start or resumption of racing.',
                    'options' => ['Red', 'Yellow', 'Green', 'Black'],
                    'correct_option' => 2,
                ],
                [
                    'question_text' => 'How many players are there in a volleyball team on court?',
                    'explanation' => 'Each volleyball team has six players on court.',
                    'options' => ['5', '6', '7', '8'],
                    'correct_option' => 1,
                ],
            ],
        ];

        foreach ($quizQuestions as $quizTitle => $questions) {
            $quiz = Quiz::where('title', $quizTitle)->firstOrFail();

            foreach ($questions as $questionData) {
                $question = Question::updateOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'question_text' => $questionData['question_text'],
                    ],
                    [
                        'explanation' => $questionData['explanation'],
                    ]
                );

                $question->options()->delete();

                foreach ($questionData['options'] as $index => $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => $index === $questionData['correct_option'],
                    ]);
                }
            }
        }
    }
}

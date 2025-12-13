cd whisper.cpp
sh ./models/download-ggml-model.sh base
cmake -B build
cmake --build build -j --config Release

# Testing default sample
./build/bin/whisper-cli -f samples/jfk.wav --no-prints --no-timestamps

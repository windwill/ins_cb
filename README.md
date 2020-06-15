# ins_cb
Testing Insurance Chatbot

This repository contains the source codes used for some prototypes developed for the research project "Insurance Chatbot" sponsored by FUNDACION MAPFRE.

- chatqaa.php: prototype chatbot for insurance Q&A. It is hosted @ http://swinsolutions.com/chatbot/chatqaa.php
- chatplan.php: prototype chatbot for insurance recommendation. It is hosted @ http://swinsolutions.com/chatbot/chatplan.php
- chatreddit.php: prototype chatbot based on Reddit contents. It is hosted @ http://swinsolutions.com/chatbot/chatreddit.php

s2s_ins: it is a seq2seq model using Reddit contents related to insurance. It follows the tensorflow 0.9 translate example: https://github.com/petewarden/tensorflow_makefile/blob/master/tensorflow/g3doc/tutorials/recurrent/index.md
- s2s_ins.ini: change parameters for model training
- based on the mode (train or test) in s2s_ins.ini, run "python run.py" will either train the model or test the chatbot. For a reasonable trained model, it may take days for the perplexity to fall below 2.



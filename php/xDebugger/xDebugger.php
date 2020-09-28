<?php
    class xDebugger{

        private $start = array();
        private $name = array();
        private $end  = array();
        private $line = array();
        private $as_new;
        private $run;

        function __construct(bool $as_new = false, bool $run = true){          
            $this->as_new = $as_new;
            $this->run = $run;
        }

        public function set_s(string $method_name = '', string $starting_name = ''): void{
            if(!$this->run){
                return;
            }

            if($starting_name){
                echo $starting_name;
            }

            array_push($this->start, microtime(true));

            if($method_name){
                array_push($this->name, $method_name);
            }else{
                array_push($this->name, 'Method index: ' . count($this->start));
            }
        }

        public function set_e(): void{
            if(!$this->run){
                return;
            }

            array_push($this->end, microtime(true));
        }

        public function cal(bool $show = true): string{
            if(!$this->run){
                return 'Status run : FALSE';
            }

            if(count($this->end) !== count($this->start)){
                throw new xException('COUNT != ');
            }

            if(!$this->as_new){
                $this->end = array_reverse($this->end);
                foreach($this->start as $key => $row){
                    $line = $this->to_string_line($key, $this->name[$key], $this->start[$key],$this->end[$key]);
                    array_push($this->line, $line);
                }
                if($show){
                    echo implode ( '<br>' , $this->line). '<br>'. '<br>';
                }
                return implode ( '<br>' , $this->line). '<br>'. '<br>';
            }else{
                $line = $this->to_string_line(count($this->start) - 1, end($this->name), end($this->start), end($this->end));
                if($show){
                    echo $line . '<br>' . '<br>';
                }
                return $line . '<br>' . '<br>';
            }
        }

        private function to_string_line($index, $name, $start, $end): string{
            return $index . '# ' . $name . '; Execution time: '. number_format(($end - $start), 4) . ' s';
        }
    }
?>
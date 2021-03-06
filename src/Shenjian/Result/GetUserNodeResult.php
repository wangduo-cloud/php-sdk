<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

namespace Shenjian\Result;


use Shenjian\Model\Node;

class GetUserNodeResult extends Result
{
    protected function parseDataFromResponse(){
        $content = $this->data;
        $node_all = isset($content['node_all']) ? intval($content['node_all']) : 0;
        $node_running = isset($content['node_running']) ? intval($content['node_running']) : 0;
        $node_gpu_all = isset($content['node_gpu_all']) ? intval($content['node_gpu_all']) : 0;
        $node_gpu_running = isset($content['node_gpu_running']) ? intval($content['node_gpu_running']) : 0;
        $node = new Node();
        $node->setNodeAll($node_all);
        $node->setNodeRunning($node_running);
        $node->setNodeGpuAll($node_gpu_all);
        $node->setNodeGpuRunning($node_gpu_running);
        return $node;
    }
}
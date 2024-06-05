import Vue from 'Vue';
import SlideShow from './SlideShow.vue';

window.ProcessMaker.EventBus.$on("modeler-start", ({ loadXML }) => {
  loadXML(
    '<?xml version="1.0" encoding="UTF-8"?>\n<bpmn:definitions xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:pm="http://processmaker.com/BPMN/2.0/Schema.xsd" xmlns:tns="http://sourceforge.net/bpmn/definitions/_1530553328908" xmlns:xsd="http://www.w3.org/2001/XMLSchema" targetNamespace="http://bpmn.io/schema/bpmn" exporter="ProcessMaker Modeler" exporterVersion="1.0" xsi:schemaLocation="http://www.omg.org/spec/BPMN/20100524/MODEL http://bpmn.sourceforge.net/schemas/BPMN20.xsd">\n  <bpmn:process id="ProcessId" name="ProcessName" isExecutable="true">\n    <bpmn:startEvent id="node_1" name="Start Event" pm:allowInterstitial="true" pm:interstitialScreenRef="1">\n      <bpmn:outgoing>node_11</bpmn:outgoing>\n    </bpmn:startEvent>\n    <bpmn:task id="node_2" name="Form Task" pm:screenRef="5" pm:allowInterstitial="false" pm:assignment="requester" pm:assignmentLock="false" pm:allowReassignment="false">\n      <bpmn:incoming>node_11</bpmn:incoming>\n      <bpmn:outgoing>node_30</bpmn:outgoing>\n    </bpmn:task>\n    <bpmn:sequenceFlow id="node_11" sourceRef="node_1" targetRef="node_2" />\n    <bpmn:endEvent id="node_22" name="End Event">\n      <bpmn:incoming>node_30</bpmn:incoming>\n    </bpmn:endEvent>\n    <bpmn:sequenceFlow id="node_30" sourceRef="node_2" targetRef="node_22" />\n  </bpmn:process>\n  <bpmndi:BPMNDiagram id="BPMNDiagramId">\n    <bpmndi:BPMNPlane id="BPMNPlaneId" bpmnElement="ProcessId">\n      <bpmndi:BPMNShape id="node_1_di" bpmnElement="node_1">\n        <dc:Bounds x="212" y="291" width="36" height="36" />\n      </bpmndi:BPMNShape>\n      <bpmndi:BPMNShape id="node_2_di" bpmnElement="node_2">\n        <dc:Bounds x="354" y="275" width="116" height="76" />\n      </bpmndi:BPMNShape>\n      <bpmndi:BPMNEdge id="node_11_di" bpmnElement="node_11">\n        <di:waypoint x="230" y="309" />\n        <di:waypoint x="412" y="313" />\n      </bpmndi:BPMNEdge>\n      <bpmndi:BPMNShape id="node_22_di" bpmnElement="node_22">\n        <dc:Bounds x="553" y="290" width="36" height="36" />\n      </bpmndi:BPMNShape>\n      <bpmndi:BPMNEdge id="node_30_di" bpmnElement="node_30">\n        <di:waypoint x="412" y="313" />\n        <di:waypoint x="571" y="308" />\n      </bpmndi:BPMNEdge>\n    </bpmndi:BPMNPlane>\n  </bpmndi:BPMNDiagram>\n</bpmn:definitions>'
  );
});

new Vue({
  el: '#app-package-show',
  components: { SlideShow }  
});



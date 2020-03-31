@echo OFF

set JAVA_COMMAND=java
@echo ON

%JAVA_COMMAND% -ea -Xmx700m -Dswing.aatext=true -Djava.library.path="application;ext/jogl2/lib" -Dorg.alice.ide.simsResourcePath="./gallery/assets/sims" -Dorg.alice.ide.modelResourcePath="./gallery/assets/alice" -Djava.util.Arrays.useLegacyMergeSort=true -classpath "ext/alice/aliceModelSource.jar;ext/alice/nebulousModelSource.jar;ext/apple/AppleJavaExtensions.jar;ext/guava/lib/guava-14.0.1.jar;ext/jaf/activation.jar;ext/jira/jira-soapclient-4.0-with-dependencies.jar;ext/jmf/JMF-2.1.1e/lib/customizer.jar;ext/jmf/JMF-2.1.1e/lib/jmf.jar;ext/jmf/JMF-2.1.1e/lib/mediaplayer.jar;ext/jmf/JMF-2.1.1e/lib/multiplayer.jar;ext/jmfmp3/lib/ext/mp3plugin.jar;ext/jogl2/lib/gluegen-rt.jar;ext/jogl2/lib/jogl-all.jar;ext/mail/lib/mailapi.jar;ext/mig_layout/miglayout-4.0-swing.jar;ext/mmsc/mmsc.jar;ext/rpc/xmlrpc-client-1.1.jar;ext/swingworker/swing-worker-1.2.jar;ext/tools/tools.jar;ext/vlcj/jna-3.5.2.jar;ext/vlcj/platform-3.5.2.jar;ext/vlcj/vlcj-2.4.1.jar;ext/wrapped_flow_layout/wrapped_flow_layout.jar;ext/youtube/lib/gdata-client-1.0.jar;ext/youtube/lib/gdata-client-meta-1.0.jar;ext/youtube/lib/gdata-core-1.0.jar;ext/youtube/lib/gdata-media-1.0.jar;ext/youtube/lib/gdata-youtube-2.0.jar;ext/youtube/lib/gdata-youtube-meta-2.0.jar;lib/alice/alicecore.jar" org.alice.stageide.EntryPoint %1

pause
